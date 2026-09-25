<?php
/**
 * MMCS ADMIN ACCOUNT SETUP
 * ========================
 * Creates or updates THE admin account for this installation. Because this
 * script can delete other admin rows, it enforces a single-admin policy:
 * the username you supply is the only account left in the `admins` table.
 *
 * No credential is ever stored in this file or in schema.sql — the password
 * is read at runtime and only its bcrypt hash reaches the database.
 *
 * Usage (interactive):
 *   php scripts/set_admin.php
 *
 * Usage (non-interactive, for automation):
 *   $env:MMCS_ADMIN_USERNAME = "you@example.com"
 *   $env:MMCS_ADMIN_PASSWORD = "your-password"
 *   php scripts/set_admin.php --yes
 *
 * Flags:
 *   --yes           Skip the confirmation prompt (required for non-interactive use)
 *   --keep-others   Do not delete other admin accounts
 *   --force         Apply a password that fails the strength policy (last resort)
 *
 * DESIGN & DEVELOPMENT: Vishwas Suthar
 */

// ---------------------------------------------------
//  SECURITY: Block direct browser access to this file
// ---------------------------------------------------
if (php_sapi_name() !== 'cli') {
    http_response_code(403);
    exit('This script can only be run from the command line.');
}

require_once dirname(__DIR__) . '/includes/db.php';
require_once dirname(__DIR__) . '/includes/functions.php';

// ---------------------------------------------------
//  ARGUMENTS
// ---------------------------------------------------
$opts = getopt('', ['yes', 'keep-others', 'force']);
$assume_yes  = array_key_exists('yes', $opts);
$keep_others = array_key_exists('keep-others', $opts);
$force       = array_key_exists('force', $opts);

// ---------------------------------------------------
//  PROMPT HELPERS
// ---------------------------------------------------

/**
 * Read a line from stdin without echoing it, so the password is not
 * left visible in scrollback or a screen recording. Falls back to a
 * visible prompt if the terminal cannot be put into no-echo mode.
 */
function prompt_hidden(string $prompt): string
{
    fwrite(STDOUT, $prompt);

    if (DIRECTORY_SEPARATOR === '/') {
        @shell_exec('stty -echo');
        $value = fgets(STDIN);
        @shell_exec('stty echo');
        fwrite(STDOUT, PHP_EOL);
        return $value === false ? '' : trim($value);
    }

    // Windows: Read-Host -AsSecureString suppresses the console echo, then the
    // value is marshalled back out for us. cmd.exe passes the single-quoted
    // -Command argument through untouched, and the script contains no
    // single quotes of its own, so no nested-escaping is needed.
    $cmd = 'powershell -NoProfile -Command '
        . '"$s = Read-Host -AsSecureString; '
        . '$b = [Runtime.InteropServices.Marshal]::SecureStringToBSTR($s); '
        . '[Runtime.InteropServices.Marshal]::PtrToStringBSTR($b); '
        . '[Runtime.InteropServices.Marshal]::ZeroFreeBSTR($b)"';
    $value = shell_exec($cmd);

    if ($value === null || $value === false) {
        fwrite(STDOUT, PHP_EOL . '(warning: could not hide input, echoing instead)' . PHP_EOL);
        $value = fgets(STDIN);
    }
    return $value === false ? '' : trim($value);
}

function prompt_line(string $prompt, string $default = ''): string
{
    $suffix = $default !== '' ? " [$default]" : '';
    fwrite(STDOUT, $prompt . $suffix . ': ');
    $value = fgets(STDIN);
    $value = $value === false ? '' : trim($value);
    return $value === '' ? $default : $value;
}

// ---------------------------------------------------
//  COLLECT CREDENTIALS
// ---------------------------------------------------
$username = getenv('MMCS_ADMIN_USERNAME') ?: prompt_line('Admin username (email works well)');
$password = getenv('MMCS_ADMIN_PASSWORD') ?: prompt_hidden('Admin password: ');

if ($password === '') {
    $confirm = prompt_hidden('Confirm password: ');
    if ($confirm !== $password) {
        fwrite(STDERR, "Passwords do not match. Aborted." . PHP_EOL);
        exit(1);
    }
}

$errors = [];
if ($username === '') {
    $errors[] = 'username must not be empty';
} elseif (mb_strlen($username) > 100) {
    $errors[] = 'username must be 100 characters or fewer (admins.username is VARCHAR(100))';
}
if ($password === '') {
    $errors[] = 'password must not be empty';
}

if ($errors) {
    foreach ($errors as $e) {
        fwrite(STDERR, 'Error: ' . $e . PHP_EOL);
    }
    exit(1);
}

// ---------------------------------------------------
//  STRENGTH POLICY (shared with admin/settings.php)
// ---------------------------------------------------
$strength_error = validate_password_strength($password, $username);

if ($strength_error !== null) {
    fwrite(STDERR, 'Password rejected:' . PHP_EOL . '  ' . $strength_error . PHP_EOL);
    if (!$force) {
        fwrite(STDERR, PHP_EOL . 'Choose a stronger password, or pass --force to override' . PHP_EOL
            . '(last resort only: a weak password here is reachable by anyone who finds it).' . PHP_EOL);
        exit(1);
    }
    fwrite(STDERR, PHP_EOL . 'WARNING: --force given, applying a password that fails policy.' . PHP_EOL);
}

// ---------------------------------------------------
//  PREVIEW CHANGES
// ---------------------------------------------------
$existing = $pdo->query("SELECT `id`, `username` FROM `admins` ORDER BY `id`")->fetchAll();
$doomed = array_values(array_filter($existing, fn($a) => $a['username'] !== $username));

fwrite(STDOUT, PHP_EOL . 'Admins table currently holds:' . PHP_EOL);
if (!$existing) {
    fwrite(STDOUT, '  (empty)' . PHP_EOL);
}
foreach ($existing as $a) {
    $mark = $a['username'] === $username ? ' <- will be set to the new password' : ($keep_others ? '' : ' <- WILL BE DELETED');
    fwrite(STDOUT, '  #' . $a['id'] . ' ' . $a['username'] . $mark . PHP_EOL);
}
fwrite(STDOUT, PHP_EOL . 'Result: exactly one admin account, "' . $username . '".' . PHP_EOL);

if (!$assume_yes) {
    $answer = prompt_line('Proceed? (type yes to continue)');
    if (strtolower($answer) !== 'yes') {
        fwrite(STDOUT, 'Aborted. No changes made.' . PHP_EOL);
        exit(0);
    }
}

// ---------------------------------------------------
//  APPLY
// ---------------------------------------------------
$hash = password_hash($password, PASSWORD_DEFAULT);

$pdo->beginTransaction();
try {
    if (!$keep_others && $doomed) {
        $placeholders = implode(',', array_fill(0, count($doomed), '?'));
        $pdo->prepare("DELETE FROM `admins` WHERE `id` IN ($placeholders)")
            ->execute(array_column($doomed, 'id'));
    }

    $stmt = $pdo->prepare(
        "INSERT INTO `admins` (`username`, `password_hash`) VALUES (?, ?)
         ON DUPLICATE KEY UPDATE `password_hash` = VALUES(`password_hash`)"
    );
    $stmt->execute([$username, $hash]);

    $pdo->commit();
} catch (PDOException $e) {
    $pdo->rollBack();
    fwrite(STDERR, 'Database error: ' . $e->getMessage() . PHP_EOL);
    exit(1);
}

// ---------------------------------------------------
//  VERIFY (do not trust the write blindly)
// ---------------------------------------------------
$check = $pdo->prepare("SELECT `id`, `username` FROM `admins` WHERE `username` = ?");
$check->execute([$username]);
$saved = $check->fetch();
$total = (int) $pdo->query("SELECT COUNT(*) FROM `admins`")->fetchColumn();

if (!$saved) {
    fwrite(STDERR, 'FAILED: account was not found after the write.' . PHP_EOL);
    exit(1);
}
if (!$keep_others && $total !== 1) {
    fwrite(STDERR, 'FAILED: expected exactly 1 admin row, found ' . $total . '.' . PHP_EOL);
    exit(1);
}

fwrite(STDOUT, PHP_EOL . 'OK: admin #' . $saved['id'] . ' "' . $saved['username'] . '" is ready.'
    . PHP_EOL . 'Login at /admin/login.php' . PHP_EOL);
