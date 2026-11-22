<?php
/**
 * monospace.com control - the control screen for this site's home page (https://www.monospace.com/control/).
 *
 * The real home page as a live preview (it listens for the CSS this screen posts when loaded with ?ms_preview=1),
 * folding panels, nothing changes on the site until Apply. Today: the Spotlight panel. Panels and spotlight images next.
 * Login: one password (hash in ../../control-data, outside the web root).
 */

define('MS_CONTROL', 1);
require __DIR__ . '/lib.php';
ini_set('error_log', ms_data_dir() . '/php_errorlog'); // this screen's PHP errors go outside the web root, not into a file in /control/

header('Cache-Control: no-store');
header('X-Robots-Tag: noindex, nofollow');
header('X-Frame-Options: SAMEORIGIN');

$https = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
session_name('ms_control');
session_set_cookie_params(['path' => '/control/', 'secure' => $https, 'httponly' => true, 'samesite' => 'Strict', 'lifetime' => 0]);
session_start();
if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16));

$h = fn($s) => htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
$self = strtok($_SERVER['REQUEST_URI'] ?? '/control/', '?');
$redirect = function (string $q = '') use ($self) { header('Location: ' . $self . $q); exit; };
$posted = $_SERVER['REQUEST_METHOD'] === 'POST';
$msg = '';

if ($posted && !hash_equals($_SESSION['csrf'], (string) ($_POST['csrf'] ?? ''))) { http_response_code(400); exit('Bad request - reload the page.'); }
$action = $posted ? (string) ($_POST['action'] ?? '') : '';

if ($action === 'login') {
    if (ms_locked()) {
        $msg = 'Too many tries. Wait ten minutes.';
    } elseif (ms_auth_hash() !== '' && password_verify((string) ($_POST['password'] ?? ''), ms_auth_hash())) {
        session_regenerate_id(true);
        $_SESSION['in'] = 1;
        ms_throttle_clear();
        $redirect();
    } else {
        ms_throttle_add();
        sleep(1);
        $msg = 'That is not the password.';
    }
} elseif ($action === 'logout') {
    $_SESSION = [];
    session_destroy();
    $redirect();
}

$in = !empty($_SESSION['in']);

if ($in && $action === 'password') {
    $new = (string) ($_POST['new'] ?? '');
    if (!password_verify((string) ($_POST['current'] ?? ''), ms_auth_hash())) $redirect('?pw=wrong');
    if (strlen($new) < 10) $redirect('?pw=short');
    $redirect(ms_set_password($new) ? '?pw=ok' : '?pw=fail');
}
if ($in && $action === 'objpreview') { // the generated image for the settings on the screen, not saved: a PNG
    $id = (string) ($_POST['id'] ?? '');
    $src = preg_match('/^[a-f0-9]{8}$/', $id) && is_readable(ms_object_files($id)['src']) ? ms_load_picture(ms_object_files($id)['src']) : null;
    if (!$src) { http_response_code(404); exit; }
    header('Content-Type: image/png');
    imagepng(ms_render_object($src, ms_object_settings(json_decode((string) ($_POST['settings'] ?? ''), true))), null, 1);
    exit;
}
if ($in && in_array($action, ['objnew', 'objsave', 'delimg', 'logoup'], true)) { // answered as JSON, called by the screen's script
    header('Content-Type: application/json');
    if ($action === 'logoup') {
        $f = $_FILES['picture'] ?? null;
        if (!$f || $f['error'] !== UPLOAD_ERR_OK || $f['size'] > 12 * 1024 * 1024) exit(json_encode(['error' => 'Choose a picture under 12 MB.']));
        $r = ms_save_logo($f['tmp_name']);
        exit(json_encode($r[0] === '!' ? ['error' => substr($r, 1)] : ['path' => $r]));
    }
    if ($action === 'objnew') {
        $f = $_FILES['picture'] ?? null;
        if (!$f || $f['error'] !== UPLOAD_ERR_OK || $f['size'] > 12 * 1024 * 1024) exit(json_encode(['error' => 'Choose a picture under 12 MB.']));
        $r = ms_object_new($f['tmp_name'], (string) ($_POST['mode'] ?? ''));
        exit(json_encode(is_string($r) ? ['error' => substr($r, 1)] : ['id' => $r[0], 'path' => $r[1], 'settings' => $r[2]]));
    }
    if ($action === 'objsave') {
        $id = (string) ($_POST['id'] ?? '');
        if (!preg_match('/^[a-f0-9]{8}$/', $id)) exit(json_encode(['error' => 'Unknown image.']));
        $o = ms_object_settings(json_decode((string) ($_POST['settings'] ?? ''), true));
        $r = ms_object_make($id, $o);
        exit(json_encode($r[0] === '!' ? ['error' => substr($r, 1)] : ['path' => $r, 'settings' => $o]));
    }
    $r = ms_delete_image((string) ($_POST['path'] ?? ''));
    exit(json_encode($r === true ? ['ok' => 1] : ['error' => $r]));
}
if ($in && $action === 'save') {
    $p = $_POST['spot'] ?? [];
    $tj   = json_decode((string) ($_POST['tone_json'] ?? ''), true); // a stale copy of the screen sends nothing - keep what is published
    $tone = is_array($tj) && $tj ? $tj : ms_spotlight_settings()['tone'];
    $spot = ms_spotlight_settings([
        'on'    => empty($p['on']) ? 0 : 1,
        'pos'   => is_array($p['pos'] ?? null) ? $p['pos'] : [],
        'tone'  => $tone,
    ]);
    $parts = ['spotlight' => $spot];
    // Panels and look are replaced only when the screen sent them (a stale copy of the screen, or a failed script, sends nothing - and must not reset them)
    $pj = json_decode((string) ($_POST['panels_json'] ?? ''), true);
    $lj = json_decode((string) ($_POST['look_json'] ?? ''), true);
    if (is_array($pj) && $pj) $parts['panels'] = ms_panels_settings($pj);
    if (is_array($lj) && $lj) $parts['look'] = ms_look_settings($lj);
    $sj = json_decode((string) ($_POST['site_json'] ?? ''), true);
    if (is_array($sj) && $sj) $parts['site'] = ms_site_settings($sj);
    $r = ms_publish($parts);
    $redirect($r === true ? '?updated=1' . (isset($parts['panels']) ? '' : '&partial=1') : '?error=' . rawurlencode($r));
}

/* ------------------------------------------------------------ login page ------------------------------------------------------------ */
$style = <<<'CSS'
:root { color-scheme: light; }
* { box-sizing: border-box; }
body { margin: 0; font: 14px/1.5 -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; background: #f0f0f1; color: #1d2327; }
button, input, select { font: inherit; }
.button { background: #2271b1; color: #fff; border: 1px solid #2271b1; border-radius: 3px; padding: 6px 14px; cursor: pointer; }
.button:hover { background: #135e96; }
.button.secondary { background: #f6f7f7; color: #2271b1; border-color: #2271b1; }
.link { background: none; border: 0; padding: 0; color: #2271b1; text-decoration: underline; cursor: pointer; }
.notice { border-left: 4px solid #2271b1; background: #fff; padding: 8px 12px; margin: 12px 0; box-shadow: 0 1px 1px rgba(0,0,0,.04); }
.notice.ok { border-color: #00a32a; } .notice.bad { border-color: #d63638; }
CSS;
if (!$in) {
    ?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="robots" content="noindex">
    <title>monospace.com control</title><style><?= $style ?>
    .login { max-width: 320px; margin: 12vh auto; background: #fff; border: 1px solid #c3c4c7; border-radius: 6px; padding: 24px; }
    .login h1 { font-size: 20px; margin: 0 0 16px; } .login input[type=password] { width: 100%; padding: 8px; margin: 6px 0 14px; border: 1px solid #8c8f94; border-radius: 4px; }</style></head>
    <body><form class="login" method="post" action="<?= $h($self) ?>">
        <h1>monospace.com control</h1>
        <?php if ($msg) echo '<div class="notice bad">' . $h($msg) . '</div>'; ?>
        <?php if (ms_auth_hash() === '') echo '<div class="notice bad">No password is set yet.</div>'; ?>
        <input type="hidden" name="csrf" value="<?= $h($_SESSION['csrf']) ?>"><input type="hidden" name="action" value="login">
        <label for="pw">Password</label><input type="password" id="pw" name="password" autofocus autocomplete="current-password">
        <button class="button" type="submit">Log in</button>
    </form></body></html><?php
    exit;
}

/* ------------------------------------------------------------ the screen ------------------------------------------------------------ */
$s       = ms_spotlight_settings();
$def     = ms_spotlight_defaults();
$devices = ms_devices();
$cfg     = ms_config();
$js_dev  = [];
foreach ($devices as $k => $d) $js_dev[$k] = ['label' => $d[0], 'w' => $d[2], 'h' => $d[3]];
$fields  = [['Across', 0, 100, 'vw'], ['Down', 0, 100, 'vh'], ['Bright core', 0, 100, '%'], ['Reach', 10, 250, 'vw'], ['Light from', 0, 359, '°']];
$pw      = $_GET['pw'] ?? '';
$panelsNow = ms_panels_now();
$look    = ms_look_settings();
$site    = ms_site_settings();
$defs    = ms_defaults();
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="robots" content="noindex">
<title>monospace.com control</title>
<style><?= $style ?>
.wrap { max-width: 1500px; margin: 0 auto; padding: 16px 20px 0; }
.top { display: flex; align-items: baseline; gap: 16px; } .top h1 { margin: 0 0 4px; font-size: 23px; font-weight: 400; } .top form { margin-left: 0; }
.where { margin: 4px 0 14px; max-width: 900px; color: #50575e; }
.grid { display: grid; grid-template-columns: minmax(0, 1.25fr) minmax(300px, 1fr); gap: 18px; align-items: start; }
@media (max-width: 1100px) { .grid { grid-template-columns: minmax(0, 1fr); } .preview { position: static; } }
.preview { position: sticky; top: 10px; background: #fff; border: 1px solid #c3c4c7; border-radius: 6px; padding: 10px; }
.bar { display: flex; flex-wrap: wrap; gap: 6px 10px; align-items: center; margin-bottom: 8px; color: #646970; }
.bar label { white-space: nowrap; }
.stage { position: relative; overflow: hidden; background: #000; border-radius: 4px; margin: 0 auto; }
.stage iframe { border: 0; position: absolute; left: 0; top: 0; transform-origin: 0 0; background: #000; }
.dot { position: absolute; width: 30px; height: 30px; margin: -15px 0 0 -15px; border-radius: 50%; border: 2px solid #fff; box-shadow: 0 0 0 1px #135e96, 0 0 8px rgba(0,0,0,.6); background: rgba(34,113,177,.35); cursor: grab; touch-action: none; }
.dot:active { cursor: grabbing; }
.panels { display: grid; gap: 10px; }
.panel { background: #fff; border: 1px solid #c3c4c7; border-radius: 6px; }
.panel > summary { display: flex; flex-wrap: wrap; align-items: baseline; gap: 4px 10px; padding: 12px 14px; cursor: pointer; list-style: none; }
.panel > summary::-webkit-details-marker { display: none; }
.panel > summary::before { content: "▸"; color: #8c8f94; width: 10px; } .panel[open] > summary::before { content: "▾"; }
.panel[open] > summary { border-bottom: 1px solid #f0f0f1; }
.t { font-weight: 600; } .sub { color: #646970; font-size: 12px; flex-basis: 100%; padding-left: 20px; }
.chip { font-size: 11px; padding: 1px 8px; border-radius: 10px; background: #f0f0f1; color: #50575e; } .chip.is-on { background: #e7f0fa; color: #135e96; } .chip.is-off { background: #f6f7f7; color: #8c8f94; }
.body { padding: 8px 14px 12px; }
.row { display: grid; grid-template-columns: 120px minmax(0, 1fr); gap: 8px 12px; align-items: center; padding: 7px 0; border-top: 1px solid #f0f0f1; }
.row:first-child { border-top: 0; }
.range { display: flex; align-items: center; gap: 10px; } .range input[type=range] { flex: 1; min-width: 0; }
.range output { min-width: 52px; text-align: right; color: #646970; font-variant-numeric: tabular-nums; }
.colour { display: flex; align-items: center; gap: 10px; }
.colour input[type=color] { width: 44px; height: 30px; padding: 0; border: 1px solid #8c8f94; border-radius: 4px; background: none; }
.note { color: #646970; margin: 6px 0 0; }
.save { position: sticky; bottom: 0; background: #f0f0f1; padding: 12px 0; margin-top: 12px; display: flex; gap: 12px; align-items: center; }
.pl { display: grid; gap: 6px; padding: 8px 14px 12px; }
.pp { border: 1px solid #dcdcde; border-radius: 5px; background: #fff; }
.pp > summary { display: flex; align-items: center; gap: 8px; padding: 8px 10px; cursor: pointer; list-style: none; }
.pp > summary::-webkit-details-marker { display: none; }
.pp > summary::before { content: "▸"; color: #8c8f94; } .pp[open] > summary::before { content: "▾"; }
.pp .nm { flex: 1; min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-weight: 600; }
.pp.hid .nm { color: #8c8f94; font-weight: 400; }
.pp .mv { border: 1px solid #dcdcde; background: #f6f7f7; border-radius: 3px; padding: 0 7px; cursor: pointer; line-height: 22px; }
.pp .pb { padding: 4px 12px 12px; border-top: 1px solid #f0f0f1; }
.pp .f { display: grid; grid-template-columns: 90px minmax(0,1fr); gap: 6px 10px; align-items: start; padding: 5px 0; }
.pp input[type=text], .pp textarea, .upl input[type=text] { width: 100%; padding: 6px 8px; border: 1px solid #8c8f94; border-radius: 4px; }
.pp textarea { min-height: 96px; resize: vertical; }
.thumbs { display: flex; flex-wrap: wrap; gap: 6px; }
.th { position: relative; width: 44px; height: 70px; border: 2px solid transparent; border-radius: 4px; background: #5d6b99 center bottom / contain no-repeat; cursor: pointer; padding: 0; }
.th.sel { border-color: #2271b1; box-shadow: 0 0 0 1px #2271b1; }
.th.none { background: repeating-linear-gradient(45deg,#f0f0f1,#f0f0f1 6px,#fff 6px,#fff 12px); font-size: 11px; color: #646970; }
.th.custom::after { content: "★"; position: absolute; left: 1px; top: 0; color: #ffd24a; font-size: 12px; text-shadow: 0 0 2px #000; }
.gal { position: relative; } .gal .th { position: relative; } .gal .x { position: absolute; right: -4px; top: -4px; width: 18px; height: 18px; border-radius: 50%; border: 1px solid #d63638; background: #fff; color: #d63638; line-height: 14px; padding: 0; cursor: pointer; }
.gitem { position: relative; }
.gal .e { position: absolute; left: -4px; top: -4px; width: 18px; height: 18px; border-radius: 50%; border: 1px solid #2271b1; background: #fff; color: #2271b1; font-size: 11px; line-height: 14px; cursor: pointer; padding: 0; }
.objed { margin-top: 12px; padding: 10px 12px; background: #f6f7f7; border: 1px solid #dcdcde; border-radius: 4px; }
.objhead { display: flex; justify-content: space-between; gap: 8px; flex-wrap: wrap; margin-bottom: 8px; }
.objgrid { display: flex; gap: 14px; flex-wrap: wrap; align-items: flex-start; }
.objprev { flex: 0 0 auto; background: radial-gradient(ellipse at 50% 70%, #1a2a6c, #060818); border-radius: 6px; line-height: 0; }
.objprev img { display: block; width: 240px; height: 540px; max-width: 100%; opacity: 1; transition: opacity .15s; }
.objprev img.busy { opacity: .55; }
.objctl { flex: 1 1 260px; min-width: 240px; }
.objctl h4 { margin: 10px 0 2px; font-size: 13px; } .objctl h4:first-child { margin-top: 0; }
.objctl .row { grid-template-columns: 110px minmax(0, 1fr); padding: 3px 0; border: 0; }
.dh { cursor: grab; touch-action: none; padding: 0 6px; color: #8c8f94; font-size: 16px; line-height: 1; user-select: none; -webkit-user-select: none; }
.dh:active { cursor: grabbing; }
.pp.drag { opacity: .65; box-shadow: 0 4px 14px rgba(0,0,0,.25); }
.own { margin-top: 6px; padding: 6px 10px 2px; background: #f6f7f7; border-radius: 4px; }
.own .row { grid-template-columns: 110px minmax(0, 1fr); padding: 4px 0; border: 0; }
.addrow { display: flex; gap: 10px; align-items: center; padding-top: 4px; }
.pg input[type=text] { width: 100%; padding: 6px 8px; border: 1px solid #8c8f94; border-radius: 4px; }
.pwf input { padding: 6px; border: 1px solid #8c8f94; border-radius: 4px; margin: 4px 0 8px; width: 100%; max-width: 280px; display: block; }
</style></head>
<body><div class="wrap">
    <div class="top"><h1>monospace.com control</h1>
        <a class="link" href="/" target="_blank" rel="noopener" style="margin-left:auto">View monospace.com ↗</a>
        <form method="post" action="<?= $h($self) ?>"><input type="hidden" name="csrf" value="<?= $h($_SESSION['csrf']) ?>"><input type="hidden" name="action" value="logout"><button class="link" type="submit">Log out</button></form></div>
    <p class="where">The home page of monospace.com, edited here with the page itself as a live preview. Drag the light in the preview to move it.
        Nothing changes on the site until you Apply. Panels, their look, the spotlight and the spotlight images are edited below.</p>
    <?php if (isset($_GET['updated'])) echo '<div class="notice ok">Applied - monospace.com shows it now.</div>' . (isset($_GET['partial']) ? '<div class="notice bad">Only the spotlight was saved - this screen did not send the panels. Reload it (Cmd/Ctrl + Shift + R) and try again.</div>' : ''); ?>
    <?php if (isset($_GET['error'])) echo '<div class="notice bad">' . $h(rawurldecode($_GET['error'])) . '</div>'; ?>
    <?php if ($pw) echo '<div class="notice ' . ($pw === 'ok' ? 'ok' : 'bad') . '">' . $h(['ok' => 'Password changed.', 'wrong' => 'The current password was wrong.', 'short' => 'Use at least 10 characters.', 'fail' => 'Could not save the new password.'][$pw] ?? '') . '</div>'; ?>

    <form method="post" action="<?= $h($self) ?>" id="form">
        <input type="hidden" name="csrf" value="<?= $h($_SESSION['csrf']) ?>"><input type="hidden" name="action" value="save">
        <input type="hidden" name="panels_json" id="panels_json"><input type="hidden" name="look_json" id="look_json"><input type="hidden" name="site_json" id="site_json">
        <div class="grid">
            <div class="preview">
                <div class="bar"><label for="device">Show as</label>
                    <select id="device"><?php foreach ($devices as $k => $d) echo '<option value="' . $h($k) . '">' . $h($d[0]) . '</option>'; ?></select>
                    <label style="margin-left:12px"><input type="checkbox" id="same"> Same on all screens</label>
                    <span class="note" style="margin:0;flex-basis:100%">Spotlight, logo, panel width and text sizes are kept per screen. Tick this to change all screens at once.</span></div>
                <div class="stage" id="stage"><iframe id="frame" name="frame" title="monospace.com preview" src="/?ms_preview=1&amp;v=<?= time() ?>"></iframe><div class="dot" id="dot" title="Drag to move the light"></div></div>
            </div>
            <div class="panels">
                <details class="panel">
                    <summary><span class="t">Panels</span> <span class="chip is-on" id="pcount"></span><span class="sub">The boxes you scroll through: text, link, spotlight image, order</span></summary>
                    <div class="pl" id="plist"></div>
                    <div class="body" style="padding-top:0"><div class="addrow"><button type="button" class="button secondary" id="padd">Add a panel</button>
                        <button type="button" class="link" id="preset">Back to the starting panels</button></div></div>
                </details>
                <details class="panel">
                    <summary><span class="t">Look of the panels</span> <span class="chip" id="lchip"></span><span class="sub">Size, glass, edge, glow and text - for all panels</span></summary>
                    <div class="body" id="lbody"></div>
                </details>
                <details class="panel">
                    <summary><span class="t">Page text, logo &amp; links</span><span class="sub">The contact link (and its button look), the line at the bottom, and the logo: picture, size, place and layer</span></summary>
                    <div class="body pg">
                        <div class="row"><label>Contact link</label><label><input type="checkbox" id="s_show"> Show it</label></div>
                        <div class="row"><label for="s_text">Link text</label><input type="text" id="s_text" maxlength="40"></div>
                        <div class="row"><label for="s_url">Link address</label><input type="text" id="s_url" placeholder="https://… or a page name"></div>
                        <div class="row"><label for="s_logo">Logo layer</label><select id="s_logo"><option value="behind">Behind the light (as it was, dimmed by the shade)</option><option value="light">Above the light, under the panels</option><option value="front">In front of everything (z-index 50)</option></select></div>
                        <div class="row"><label for="c_style">Contact link look</label><select id="c_style"><option value="plain">Plain link (as it was)</option><option value="glass">Glass - glossy, with a highlight</option><option value="flat">Flat - solid colour</option><option value="outline">Outline - coloured border</option></select></div>
                        <div class="row"><label for="c_color">Button colour</label><div class="colour"><select id="c_color"><option value="blue">Blue</option><option value="black">Black</option><option value="grey">Grey</option><option value="custom">Your colour</option></select><input type="color" id="c_custom"></div></div>
                        <div class="row"><label for="c_radius">Button corners</label><select id="c_radius"><option value="pill">Pill (round ends)</option><option value="0">Square (0)</option><option value="4">4 px</option><option value="8">8 px</option><option value="12">12 px</option><option value="16">16 px</option><option value="20">20 px</option><option value="30">30 px</option></select></div>
                        <div class="row"><label for="l_src">Logo picture</label><input type="text" id="l_src" placeholder="/assets/images/… or https://…"></div>
                        <div class="addrow"><input type="file" id="l_file" accept="image/png,image/jpeg,image/webp,image/gif"><button type="button" class="button secondary" id="l_up">Upload it</button><button type="button" class="button secondary" id="l_reset">Back to the original logo</button><span class="note" id="l_msg" style="margin:0"></span></div>
                        <p class="note">The logo's place and size and the button's text size are for <strong id="devname2">Computer</strong> (change it with <em>Show as</em>; each screen keeps its own).</p>
                        <div class="row"><label for="c_size">Button text size</label><div class="range"><input type="range" id="c_size" min="8" max="40"><output></output></div></div>
                        <div class="row"><label for="l_x">Logo across</label><div class="range"><input type="range" id="l_x" min="0" max="100" step="0.5"><output></output></div></div>
                        <div class="row"><label>Center the logo</label><label><input type="checkbox" id="l_center"> Center horizontally (stays centered at any size)</label></div>
                        <div class="row"><label for="l_y">Logo down</label><div class="range"><input type="range" id="l_y" min="-20" max="100" step="0.5"><output></output></div></div>
                        <div class="row"><label for="l_size">Logo size</label><div class="range"><input type="range" id="l_size" min="5" max="100" step="1"><output></output></div></div>
                        <div class="row"><label for="s_foot">Bottom line</label><input type="text" id="s_foot" maxlength="200"></div>
                    </div>
                </details>
                <details class="panel">
                    <summary><span class="t">Tagline</span> <span class="chip" id="tchip"></span><span class="sub">A line of text on the page: font, style, colour, size and place</span></summary>
                    <div class="body pg">
                        <div class="row"><label>Tagline</label><label><input type="checkbox" id="t_show"> Show it (on every screen)</label></div>
                        <div class="row"><label for="t_text">Text</label><input type="text" id="t_text" maxlength="160"></div>
                        <div class="row"><label for="t_font">Font</label><select id="t_font"><?php foreach (ms_tagline_fonts() as $k => [$label]) echo '<option value="' . $h($k) . '">' . $h($label) . '</option>'; ?></select></div>
                        <div class="row"><label for="t_style">Style</label><select id="t_style"><?php foreach (ms_tagline_styles() as $k => [$label]) echo '<option value="' . $h($k) . '">' . $h($label) . '</option>'; ?></select></div>
                        <div class="row"><label for="t_color">Colour</label><div class="colour"><input type="color" id="t_color"></div></div>
                        <p class="note">Place and size are for <strong id="devname3">Computer</strong> (change it with <em>Show as</em>; each screen keeps its own). The tagline always stays inside the screen and sits under the panels.</p>
                        <div class="row"><label for="t_x">Across</label><div class="range"><input type="range" id="t_x" min="0" max="100" step="0.5"><output></output></div></div>
                        <div class="row"><label>Center the tagline</label><label><input type="checkbox" id="t_center"> Center horizontally</label></div>
                        <div class="row"><label for="t_y">Down</label><div class="range"><input type="range" id="t_y" min="-20" max="100" step="0.5"><output></output></div></div>
                        <div class="row"><label for="t_size">Size</label><div class="range"><input type="range" id="t_size" min="8" max="120" step="1"><output></output></div></div>
                    </div>
                </details>
                <details class="panel">
                    <summary><span class="t">Spotlight</span> <span class="chip" id="chip"></span><span class="sub">A beam of light that lands on the page</span></summary>
                    <div class="body">
                        <div class="row"><label for="on">Spotlight</label><label><input type="checkbox" id="on" name="spot[on]" value="1" <?= $s['on'] ? 'checked' : '' ?>> On</label></div>
                        <input type="hidden" name="tone_json" id="tone_json">
                        <p class="note"><strong>Position, light &amp; shade</strong> - for <span id="devname">Computer</span> (change it with <em>Show as</em>; each device keeps its own)</p>
                        <p class="note" id="yourdev" style="margin:2px 0 6px"></p>
                        <?php foreach ($fields as $i => [$label, $min, $max, $unit]) : ?>
                            <div class="row"><label for="p<?= $i ?>"><?= $h($label) ?></label>
                                <div class="range"><input type="range" id="p<?= $i ?>" min="<?= $min ?>" max="<?= $max ?>" data-unit="<?= $h($unit) ?>"><output></output></div></div>
                        <?php endforeach; ?>
                        <?php foreach ($devices as $dev => $d) foreach ($s['pos'][$dev] as $i => $v) : ?>
                            <input type="hidden" name="spot[pos][<?= $h($dev) ?>][]" value="<?= (int) $v ?>" data-dev="<?= $h($dev) ?>" data-i="<?= $i ?>">
                        <?php endforeach; ?>
                        <p class="note" style="margin-top:10px"><strong>Light &amp; shade</strong> - the colours are the same on every screen; the strengths and the beam width are per screen</p>
                        <div class="row"><label for="light">Light</label><div class="colour"><input type="color" id="light" value="<?= $h($s['tone']['desktop'][0]) ?>">
                            <div class="range" style="flex:1"><input type="range" id="light-a" min="0" max="100" value="<?= (int) $s['tone']['desktop'][1] ?>" data-unit="%"><output></output></div></div></div>
                        <div class="row"><label for="shade">Darkness around it</label><div class="colour"><input type="color" id="shade" value="<?= $h($s['tone']['desktop'][2]) ?>">
                            <div class="range" style="flex:1"><input type="range" id="shade-a" min="0" max="100" value="<?= (int) $s['tone']['desktop'][3] ?>" data-unit="%"><output></output></div></div></div>
                        <div class="row"><label for="beam">Beam width</label><div class="range"><input type="range" id="beam" min="3" max="40" value="<?= (int) $s['tone']['desktop'][4] ?>" data-unit="°"><output></output></div></div>
                        <p class="note"><button type="button" class="link" id="reset">Back to the starting values</button></p>
                    </div>
                </details>
                <details class="panel">
                    <summary><span class="t">Spotlight images</span> <span class="chip" id="icount"></span><span class="sub">The picture beside the panels - make a new one from your own picture</span></summary>
                    <div class="body upl">
                        <div class="row"><label>Fixed oval</label><label><input type="checkbox" id="o_on"> Keep a white oval fixed behind the image - only the figure fades in and out</label></div>
                        <div class="row"><label for="o_grow">Oval larger by</label><div class="range"><input type="range" id="o_grow" min="0" max="8"><output></output></div></div>
                        <p class="note" style="margin:6px 0"><strong>Make an image from a picture.</strong> Upload an object - a <strong>cut-out</strong> (transparent PNG or WebP) works best - and the
                            spotlight image is made for you: the object, and its shadow on the white oval (the object's outline filled black, cut off by the oval's shape).</p>
                        <div class="row"><label>The object</label><span><label><input type="radio" name="objmode" value="floating" checked> Floating (like the hand)</label>
                            &nbsp; <label><input type="radio" name="objmode" value="standing"> Standing (like the easel)</label></span></div>
                        <div class="row"><label for="upfile">Picture</label><input type="file" id="upfile" accept="image/png,image/jpeg,image/webp,image/gif"></div>
                        <div class="addrow"><button type="button" class="button secondary" id="upgo">Make the image</button><span class="note" id="upmsg" style="margin:0"></span></div>
                        <div class="objed" id="objed" hidden>
                            <div class="objhead"><strong id="objname"></strong>
                                <span><label><input type="radio" name="objmode2" value="floating"> Floating</label> &nbsp; <label><input type="radio" name="objmode2" value="standing"> Standing</label></span></div>
                            <div class="objgrid">
                                <div class="objprev"><img id="objimg" alt="Preview of the spotlight image" width="240" height="540"></div>
                                <div class="objctl" id="objctl"></div>
                            </div>
                            <div class="addrow"><button type="button" class="button" id="objsave">Save this image</button><button type="button" class="button secondary" id="objreset">Back to the starting values</button>
                                <button type="button" class="link" id="objclose">Close</button><span class="note" id="objmsg" style="margin:0"></span></div>
                        </div>
                        <p class="note" style="margin-top:12px"><strong>All images</strong> - ★ made here (can be deleted when no panel uses them)</p>
                        <div class="thumbs gal" id="gal"></div>
                    </div>
                </details>
                <p class="note">Published: <?= !empty($cfg['updated']) ? $h(date('j M Y, H:i', strtotime($cfg['updated']))) : 'not yet - the site still shows its original glow' ?></p>
            </div>
        </div>
        <div class="save"><button type="submit" class="button">Apply to monospace.com</button><span class="note" style="margin:0">Saves and publishes.</span></div>
    </form>
    <form id="draftform" method="post" action="/?ms_preview=1" target="frame" style="display:none"><input type="hidden" name="draft" id="draft"></form>
    <details class="panel" style="max-width:520px;margin:6px 0 24px">
        <summary><span class="t">Password</span><span class="sub">Change the password for this screen</span></summary>
        <form class="body pwf" method="post" action="<?= $h($self) ?>"><input type="hidden" name="csrf" value="<?= $h($_SESSION['csrf']) ?>"><input type="hidden" name="action" value="password">
            <label>Current password<input type="password" name="current" autocomplete="current-password" required></label>
            <label>New password (10+ characters)<input type="password" name="new" autocomplete="new-password" minlength="10" required></label>
            <button class="button secondary" type="submit">Change password</button></form>
    </details>
</div>
<script>
(function () {
    var DEV = <?= json_encode($js_dev) ?>, DEFAULTS = <?= json_encode($def) ?>, TONE = <?= json_encode($s['tone']) ?>, MEDIA = <?= json_encode(array_map(fn($d) => $d[1], $devices)) ?>;
    var $ = function (id) { return document.getElementById(id); };
    var form = $('form'), frame = $('frame'), stage = $('stage'), dot = $('dot'), deviceSel = $('device');
    var ranges = [0, 1, 2, 3, 4].map(function (i) { return $('p' + i); });
    var hid = {}; form.querySelectorAll('input[type=hidden][data-dev]').forEach(function (e) { (hid[e.dataset.dev] = hid[e.dataset.dev] || [])[+e.dataset.i] = e; });
    var dev = 'desktop', ready = false;

    function rgba(hex, a) {
        var h = (hex || '').replace('#', ''); if (!/^[0-9a-f]{6}$/i.test(h)) h = 'ffffff';
        return 'rgba(' + parseInt(h.substr(0, 2), 16) + ', ' + parseInt(h.substr(2, 2), 16) + ', ' + parseInt(h.substr(4, 2), 16) + ', ' + Math.round(a) / 100 + ')';
    }
    function pos(d) { return hid[d].map(function (e) { return +e.value; }); }
    // The same CSS as ms_spotlight_css() in lib.php - keep the two in step
    function css() {
        if (!$('on').checked) return ':root{--spot-pool:none}body::after{display:none}';
        var mask = 'conic-gradient(from calc(var(--beam-angle) + 180deg - var(--beam-half)) at var(--sx) var(--sy),#000 0deg,rgba(0,0,0,.85) calc(var(--beam-half) * .3),rgba(0,0,0,.1) calc(var(--beam-half) * .8),transparent var(--beam-half),rgba(0,0,0,.1) calc(var(--beam-half) * 1.2),rgba(0,0,0,.85) calc(var(--beam-half) * 1.7),#000 calc(var(--beam-half) * 2)),radial-gradient(circle at var(--sx) var(--sy),transparent calc(var(--beam-len) * .9),#000 calc(var(--beam-len) + var(--gradient-radius) * 1.3))';
        var out = ':root{--beam-len:110vmax'
            + ';--spot-pool:radial-gradient(circle at var(--gradient-center-x) var(--gradient-center-y),var(--gradient-foreground) var(--gradient-start),var(--gradient-edge) var(--gradient-radius))}';
        Object.keys(DEV).forEach(function (d) {
            var p = pos(d), t = TONE[d], rule = ':root{--gradient-foreground:' + rgba(t[0], t[1]) + ';--gradient-edge:' + rgba(t[0], 0) + ';--beam-shade:' + rgba(t[2], t[3]) + ';--beam-half:' + t[4] + 'deg;--gradient-center-x:' + p[0] + 'vw;--gradient-center-y:' + p[1] + 'vh;--gradient-start:' + p[2] + '%;--gradient-radius:' + p[3] + 'vw;--beam-angle:' + p[4] + 'deg}';
            out += MEDIA[d] ? '@media ' + MEDIA[d] + '{' + rule + '}' : rule;
        });
        return out + 'body::after{content:"";position:fixed;inset:0;z-index:-2;pointer-events:none;--sx:calc(var(--gradient-center-x) + var(--beam-len) * sin(var(--beam-angle)));--sy:calc(var(--gradient-center-y) - var(--beam-len) * cos(var(--beam-angle)));background:linear-gradient(var(--beam-shade),var(--beam-shade));-webkit-mask-image:' + mask + ';mask-image:' + mask + '}';
    }
    function send() { if (ready && frame.contentWindow) frame.contentWindow.postMessage({ type: 'ms-css', spot: css(), look: window.MS && MS.lookCss ? MS.lookCss() : undefined }, location.origin); }
    window.MS = { spotCss: css, send: send, frame: frame, markReady: function (r) { ready = r; }, media: MEDIA, dev: function () { return dev; }, label: function (d) { return DEV[d || dev].label; },
        targets: function () { return $('same').checked ? Object.keys(DEV) : [dev]; } };
    function label(el) { var o = el.parentNode.querySelector('output'); if (o) o.textContent = el.value + el.dataset.unit; }
    function refresh() {
        var p = pos(dev);
        ranges.forEach(function (r, i) { r.value = p[i]; label(r); });
        var t = TONE[dev]; $('light').value = t[0]; $('light-a').value = t[1]; $('shade').value = t[2]; $('shade-a').value = t[3]; $('beam').value = t[4];
        ['light-a', 'shade-a', 'beam'].forEach(function (id) { label($(id)); });
        $('devname').textContent = DEV[dev].label;
        var on = $('on').checked; $('chip').className = 'chip ' + (on ? 'is-on' : 'is-off'); $('chip').textContent = on ? 'In use' : 'Off';
        dot.style.display = on ? '' : 'none'; dot.style.left = p[0] + '%'; dot.style.top = p[1] + '%';
    }
    function layout() {
        var w = DEV[dev].w, h = DEV[dev].h, k = Math.min(1, (stage.parentNode.clientWidth - 20) / w);
        if (dev !== 'desktop') k = Math.min(k, 640 / h);
        frame.style.width = w + 'px'; frame.style.height = h + 'px'; frame.style.transform = 'scale(' + k + ')';
        stage.style.width = Math.round(w * k) + 'px'; stage.style.height = Math.round(h * k) + 'px';
    }
    function changed() { refresh(); send(); }

    function setPos(i, v) { MS.targets().forEach(function (d) { hid[d][i].value = v; }); }
    function setTone() { // the two colours are the same on every screen; the strengths and the beam width are per screen
        var v = [$('light').value, +$('light-a').value, $('shade').value, +$('shade-a').value, +$('beam').value];
        Object.keys(TONE).forEach(function (d) { TONE[d][0] = v[0]; TONE[d][2] = v[2]; });
        MS.targets().forEach(function (d) { TONE[d][1] = v[1]; TONE[d][3] = v[3]; TONE[d][4] = v[4]; });
    }
    function deviceChanged() { layout(); refresh(); document.dispatchEvent(new CustomEvent('ms-device')); }
    ranges.forEach(function (r, i) { r.addEventListener('input', function () { setPos(i, r.value); changed(); }); });
    var same = $('same');
    same.checked = Object.keys(hid).every(function (d) { return pos(d).join() === pos('desktop').join() && TONE[d].join() === TONE.desktop.join(); });
    // Which values this very screen is using on the site (the site's breakpoints: phone up to 500px, tablet up to 920px)
    var mine = window.innerHeight <= 500 && window.innerWidth > window.innerHeight ? 'landscape' : window.innerWidth <= 500 ? 'phone' : window.innerWidth <= 920 ? 'tablet' : 'desktop';
    $('yourdev').innerHTML = 'This screen is ' + window.innerWidth + ' px wide, so the site shows you the <strong>' + DEV[mine].label + '</strong> values. ' + (mine === dev ? '' : '<button type="button" class="link" id="editmine">Edit those</button>');
    if ($('editmine')) $('editmine').addEventListener('click', function () { dev = mine; deviceSel.value = mine; deviceChanged(); $('editmine').remove(); });
    $('on').addEventListener('input', changed);
    ['light', 'light-a', 'shade', 'shade-a', 'beam'].forEach(function (id) { $(id).addEventListener('input', function () { setTone(); changed(); }); });
    deviceSel.addEventListener('change', function () { dev = deviceSel.value; deviceChanged(); });
    form.addEventListener('submit', function () { $('tone_json').value = JSON.stringify(TONE); });
    window.addEventListener('resize', layout);
    window.addEventListener('message', function (e) { if (e.origin === location.origin && e.data && e.data.type === 'ms-ready') { ready = true; send(); } });
    frame.addEventListener('load', function () { ready = false; setTimeout(function () { ready = true; send(); }, 300); });

    function drag(e) { // the light follows the pointer
        var r = stage.getBoundingClientRect();
        setPos(0, Math.max(0, Math.min(100, Math.round((e.clientX - r.left) / r.width * 100))));
        setPos(1, Math.max(0, Math.min(100, Math.round((e.clientY - r.top) / r.height * 100)))); changed();
    }
    dot.addEventListener('pointerdown', function (e) { dot.setPointerCapture(e.pointerId); dot.onpointermove = drag; e.preventDefault(); });
    dot.addEventListener('pointerup', function () { dot.onpointermove = null; });

    $('reset').addEventListener('click', function () {
        Object.keys(DEFAULTS.pos).forEach(function (d) { DEFAULTS.pos[d].forEach(function (v, i) { hid[d][i].value = v; }); });
        $('on').checked = !!DEFAULTS.on; Object.keys(DEFAULTS.tone).forEach(function (d) { TONE[d] = DEFAULTS.tone[d].slice(); }); changed();
    });
    layout(); refresh();
})();
</script>
<script>
(function () {
    var PANELS = <?= json_encode($panelsNow, JSON_UNESCAPED_SLASHES) ?>, IMAGES = <?= json_encode(ms_images()) ?>, OBJECTS = <?= json_encode((object) ms_objects()) ?>, OBJV = {}, LOOK = <?= json_encode($look) ?>, SITE = <?= json_encode($site) ?>,
        DEF = <?= json_encode(['panels' => $defs['panels'], 'look' => $defs['look'], 'site' => $defs['site']], JSON_UNESCAPED_SLASHES) ?>, CSRF = <?= json_encode($_SESSION['csrf']) ?>;
    var $ = function (id) { return document.getElementById(id); };
    var open = {}, focusId = null, timer = null;

    function el(tag, attrs, kids) {
        var n = document.createElement(tag);
        Object.keys(attrs || {}).forEach(function (k) { if (k === 'text') n.textContent = attrs[k]; else if (k.slice(0, 2) === 'on') n.addEventListener(k.slice(2), attrs[k]); else n.setAttribute(k, attrs[k]); });
        (kids || []).forEach(function (c) { n.appendChild(c); });
        return n;
    }
    function rgba(hex, a) { var h = (hex || '').replace('#', ''); if (!/^[0-9a-f]{6}$/i.test(h)) h = 'ffffff'; return 'rgba(' + parseInt(h.substr(0, 2), 16) + ', ' + parseInt(h.substr(2, 2), 16) + ', ' + parseInt(h.substr(4, 2), 16) + ', ' + Math.round(a) / 100 + ')'; }
    // The same CSS as ms_look_css() in lib.php - keep the two in step
    function lookCss() {
        var l = LOOK, out = ':root{--panel-radius:' + l.radius + 'px;--panel-blur:' + l.blur + 'px;--panel-tint:' + rgba(l.tint[0], l.tint[1])
            + ';--panel-border-a:' + l.border / 100 + ';--panel-shine:' + l.shine / 100 + ';--panel-glow-a:' + l.glow / 100 + ';--panel-color:' + l.color + '}';
        Object.keys(MS.media).forEach(function (d) {
            var w = l.widths[d], rule = ':root{--panel-w:clamp(250px,' + w[0] + 'vw,' + w[1] + 'px);--panel-title-size:' + l.sizes[d][0] + 'px;--panel-text-size:' + l.sizes[d][1] + 'px}';
            if (d === 'phone' && String(w) !== String(DEF.look.widths.phone)) rule += '.panel-wrap{width:var(--panel-w)!important}';
            if (l.lefts[d] !== DEF.look.lefts[d]) rule += '.section{justify-content:flex-start!important;box-sizing:border-box;padding-left:' + l.lefts[d] + 'vw!important}';
            out += MS.media[d] ? '@media ' + MS.media[d] + '{' + rule + '}' : rule;
        });
        return out;
    }
    MS.lookCss = lookCss;

    // The preview: panel edits reload the real page with a draft (it opens at the panel being edited); look / spotlight edits are posted live
    function draft() {
        $('draft').value = JSON.stringify({ panels: PANELS, focus: focusId, spot: MS.spotCss(), look: lookCss(), site: SITE });
        $('draftform').submit();
    }
    function later() { clearTimeout(timer); timer = setTimeout(draft, 500); }
    function name(p) { return p.clear ? 'Text only: ' + (p.text || '').split('\n')[0].slice(0, 40) : (p.title || 'Untitled panel'); }

    function thumb(src, sel, onclick, extra) {
        var b = el('button', { type: 'button', 'class': 'th' + (sel ? ' sel' : '') + (extra || ''), title: src ? src.split('/').pop() : 'No image', onclick: onclick });
        if (src) b.style.backgroundImage = 'url(/' + src + (OBJV[src] ? '?v=' + OBJV[src] : '') + ')'; else b.textContent = 'none';
        return b;
    }
    function field(label, input) { return el('div', { 'class': 'f' }, [el('label', { text: label }), input]); }

    // Drag to reorder (pointer events, so it works with a finger on an iPad too)
    function startDrag(e, item, handle, p) {
        e.preventDefault(); item.classList.add('drag'); // listeners sit on the document: moving the item in the list would drop a pointer capture held by its handle
        var list = $('plist');
        function move(ev) {
            var target = null, kids = [].slice.call(list.children).filter(function (x) { return x !== item; });
            for (var k = 0; k < kids.length; k++) { var r = kids[k].getBoundingClientRect(); if (ev.clientY < r.top + r.height / 2) { target = kids[k]; break; } }
            list.insertBefore(item, target);
        }
        function up() {
            document.removeEventListener('pointermove', move); document.removeEventListener('pointerup', up); document.removeEventListener('pointercancel', up);
            var order = [].map.call(list.children, function (x) { return x.dataset.id; });
            PANELS.sort(function (a, b) { return order.indexOf(a.id) - order.indexOf(b.id); });
            focusId = p.id; renderPanels(); later();
        }
        document.addEventListener('pointermove', move); document.addEventListener('pointerup', up); document.addEventListener('pointercancel', up);
    }
    // A panel's own look: the same controls as the common look, for this panel only
    function ownLook(p) {
        var box = el('div', { 'class': 'own' }), l = p.look;
        function row(label, key, lo, hi, unit) {
            var out = el('output', { text: l[key] + ' ' + unit });
            var r = el('input', { type: 'range', min: lo, max: hi, value: l[key], oninput: function () { l[key] = +this.value; out.textContent = l[key] + ' ' + unit; later(); } });
            box.appendChild(el('div', { 'class': 'row' }, [el('label', { text: label }), el('div', { 'class': 'range' }, [r, out])]));
        }
        row('Width', 'width', 15, 80, '% of screen'); row('Widest', 'max', 250, 900, 'px');
        if (p.clear) { // text only: just the width and the text
            row('Text size', 'text_size', 12, 30, 'px');
            box.appendChild(el('div', { 'class': 'row' }, [el('label', { text: 'Text colour' }), el('input', { type: 'color', value: l.color || LOOK.color, style: 'width:44px;height:30px;padding:0', oninput: function () { l.color = this.value; later(); } })]));
            return box;
        }
        row('Rounded corners', 'radius', 0, 40, 'px'); row('Title size', 'title_size', 14, 48, 'px'); row('Text size', 'text_size', 12, 30, 'px');
        var tr = el('input', { type: 'range', min: 0, max: 95, value: l.tint[1], oninput: function () { l.tint[1] = +this.value; to.textContent = l.tint[1] + ' %'; later(); } }), to = el('output', { text: l.tint[1] + ' %' });
        box.appendChild(el('div', { 'class': 'row' }, [el('label', { text: 'Glass' }), el('div', { 'class': 'colour' }, [el('input', { type: 'color', value: l.tint[0], oninput: function () { l.tint[0] = this.value; later(); } }), el('div', { 'class': 'range', style: 'flex:1' }, [tr, to])])]));
        box.appendChild(el('div', { 'class': 'row' }, [el('label', { text: 'Text colour' }), el('input', { type: 'color', value: l.color, style: 'width:44px;height:30px;padding:0', oninput: function () { l.color = this.value; later(); } })]));
        return box;
    }
    // Page text & links
    $('s_show').checked = !!SITE.contact_show; $('s_text').value = SITE.contact_text; $('s_url').value = SITE.contact_url; $('s_foot').value = SITE.footer; $('s_logo').value = SITE.logo_layer;
    $('s_show').addEventListener('change', function () { SITE.contact_show = this.checked; later(); });
    $('s_text').addEventListener('input', function () { SITE.contact_text = this.value; later(); });
    $('s_url').addEventListener('input', function () { SITE.contact_url = this.value; later(); });
    $('o_on').checked = !!SITE.oval; $('o_grow').value = SITE.oval_grow; $('o_grow').nextElementSibling.textContent = SITE.oval_grow + ' px';
    $('o_on').addEventListener('change', function () { SITE.oval = this.checked; later(); });
    $('o_grow').addEventListener('input', function () { SITE.oval_grow = +this.value; this.nextElementSibling.textContent = this.value + ' px'; later(); });
    $('s_logo').addEventListener('change', function () { SITE.logo_layer = this.value; later(); });
    [['c_style', 'contact_style'], ['c_color', 'contact_color'], ['c_radius', 'contact_radius']].forEach(function (r) {
        $(r[0]).value = String(SITE[r[1]]); $(r[0]).addEventListener('change', function () { SITE[r[1]] = this.value; later(); });
    });
    $('c_custom').value = SITE.contact_custom; $('c_custom').addEventListener('input', function () { SITE.contact_custom = this.value; SITE.contact_color = 'custom'; $('c_color').value = 'custom'; later(); });
    $('l_src').value = SITE.logo_src;
    var LOGOF = [['l_x', 0, '% across'], ['l_y', 1, '% of the screen height'], ['l_size', 2, '% of the screen width']];
    function showDevice() { // the logo's and the button text's fields show the values of the screen chosen in Show as
        var d = MS.dev(); $('devname2').textContent = MS.label();
        LOGOF.forEach(function (r) { var inp = $(r[0]), v = SITE.logo_pos[d][r[1]]; inp.value = v; inp.nextElementSibling.textContent = v + ' ' + r[2]; });
        $('l_center').checked = SITE.logo_pos[d][0] === 50;
        $('c_size').value = SITE.contact_size[d]; $('c_size').nextElementSibling.textContent = SITE.contact_size[d] + ' px';
        renderLook();
    }
    LOGOF.forEach(function (r) {
        $(r[0]).addEventListener('input', function () { var n = +this.value; MS.targets().forEach(function (d) { SITE.logo_pos[d][r[1]] = n; }); this.nextElementSibling.textContent = n + ' ' + r[2]; if (r[0] === 'l_x') $('l_center').checked = n === 50; later(); });
    });
    $('l_center').addEventListener('change', function () { // centered = 50% across (the picture's middle at the screen's middle, whatever its size); unticking leaves it there to move
        if (!this.checked) return;
        MS.targets().forEach(function (d) { SITE.logo_pos[d][0] = 50; });
        $('l_x').value = 50; $('l_x').nextElementSibling.textContent = '50 % across'; later();
    });
    // The tagline: one switch for every screen; place and size per screen (like the logo)
    $('t_show').checked = !!SITE.tagline_show; $('t_text').value = SITE.tagline_text; $('t_font').value = SITE.tagline_font; $('t_style').value = SITE.tagline_style; $('t_color').value = SITE.tagline_color;
    function taglineChip() { var on = SITE.tagline_show && SITE.tagline_text.trim() !== ''; $('tchip').className = 'chip ' + (on ? 'is-on' : 'is-off'); $('tchip').textContent = on ? 'In use' : 'Off'; }
    taglineChip();
    $('t_show').addEventListener('change', function () { SITE.tagline_show = this.checked; taglineChip(); later(); });
    $('t_text').addEventListener('input', function () { SITE.tagline_text = this.value; taglineChip(); later(); });
    [['t_font', 'tagline_font'], ['t_style', 'tagline_style']].forEach(function (r) { $(r[0]).addEventListener('change', function () { SITE[r[1]] = this.value; later(); }); });
    $('t_color').addEventListener('input', function () { SITE.tagline_color = this.value; later(); });
    var TAGF = [['t_x', 0, '% across'], ['t_y', 1, '% of the screen height'], ['t_size', 2, 'px']];
    function showTagline() { var d = MS.dev(); $('devname3').textContent = MS.label();
        TAGF.forEach(function (r) { var inp = $(r[0]), v = SITE.tagline_pos[d][r[1]]; inp.value = v; inp.nextElementSibling.textContent = v + ' ' + r[2]; });
        $('t_center').checked = SITE.tagline_pos[d][0] === 50; }
    TAGF.forEach(function (r) {
        $(r[0]).addEventListener('input', function () { var n = +this.value; MS.targets().forEach(function (d) { SITE.tagline_pos[d][r[1]] = n; }); this.nextElementSibling.textContent = n + ' ' + r[2]; if (r[0] === 't_x') $('t_center').checked = n === 50; later(); });
    });
    $('t_center').addEventListener('change', function () {
        if (!this.checked) return;
        MS.targets().forEach(function (d) { SITE.tagline_pos[d][0] = 50; });
        $('t_x').value = 50; $('t_x').nextElementSibling.textContent = '50 % across'; later();
    });
    document.addEventListener('ms-device', showTagline); showTagline();
    $('c_size').addEventListener('input', function () { var n = +this.value; MS.targets().forEach(function (d) { SITE.contact_size[d] = n; }); this.nextElementSibling.textContent = n + ' px'; later(); });
    document.addEventListener('ms-device', showDevice);
    $('l_src').addEventListener('input', function () { SITE.logo_src = this.value; later(); });
    $('l_reset').addEventListener('click', function () {
        var d = DEF.site; SITE.logo_src = d.logo_src; SITE.logo_pos = JSON.parse(JSON.stringify(d.logo_pos)); $('l_src').value = d.logo_src;
        showDevice(); later();
    });
    $('l_up').addEventListener('click', function () {
        var f = $('l_file').files[0]; if (!f) { $('l_msg').textContent = 'Choose a picture first.'; return; }
        var fd = new FormData(); fd.append('action', 'logoup'); fd.append('picture', f); $('l_msg').textContent = 'Uploading…';
        post(fd).then(function (j) {
            if (j.error) { $('l_msg').textContent = j.error; return; }
            SITE.logo_src = j.path; $('l_src').value = j.path; $('l_file').value = ''; $('l_msg').textContent = 'Done - Apply to publish it.'; later();
        }).catch(function () { $('l_msg').textContent = 'The upload failed.'; });
    });
    $('s_foot').addEventListener('input', function () { SITE.footer = this.value; later(); });

    function renderPanels() {
        var list = $('plist'); list.textContent = '';
        PANELS.forEach(function (p, i) {
            var d = el('details', { 'class': 'pp' + (p.show ? '' : ' hid') }); if (open[p.id]) d.open = true;
            var nm = el('span', { 'class': 'nm', text: name(p) + (p.show ? '' : ' (hidden)') });
            var up = el('button', { type: 'button', 'class': 'mv', title: 'Move up', text: '↑', onclick: function (e) { e.preventDefault(); move(i, -1); } });
            var dn = el('button', { type: 'button', 'class': 'mv', title: 'Move down', text: '↓', onclick: function (e) { e.preventDefault(); move(i, 1); } });
            d.dataset.id = p.id;
            var dh = el('span', { 'class': 'dh', title: 'Drag to reorder', text: '⠿' });
            dh.addEventListener('click', function (e) { e.preventDefault(); e.stopPropagation(); });
            dh.addEventListener('pointerdown', function (e) { startDrag(e, d, dh, p); });
            d.appendChild(el('summary', {}, [dh, nm, up, dn]));
            var b = el('div', { 'class': 'pb' });
            if (!p.clear) {
                b.appendChild(field('Title', el('input', { type: 'text', value: p.title, maxlength: 80, oninput: function () { p.title = this.value; nm.textContent = name(p) + (p.show ? '' : ' (hidden)'); later(); } })));
            }
            var ta = el('textarea', { oninput: function () { p.text = this.value; if (p.clear) nm.textContent = name(p); later(); } }); ta.value = p.text;
            b.appendChild(field('Text', ta));
            if (!p.clear) b.appendChild(field('Link', el('input', { type: 'text', value: p.url, placeholder: 'https://… or a page name - empty = not clickable', oninput: function () { p.url = this.value; later(); } })));
            var th = el('div', { 'class': 'thumbs' });
            th.appendChild(thumb('', !p.image, function () { p.image = ''; renderPanels(); later(); }, ' none'));
            IMAGES.forEach(function (src) { th.appendChild(thumb(src, p.image === src, function () { p.image = src; renderPanels(); later(); }, /\/(custom|obj)-[a-f0-9]{8}\.png$/.test(src) ? ' custom' : '')); });
            b.appendChild(field('Spotlight image', th));
            b.appendChild(field('Look', el('label', {}, [el('input', Object.assign({ type: 'checkbox', onchange: function () {
                if (this.checked) p.look = p.clear ? { width: LOOK.widths.desktop[0], max: LOOK.widths.desktop[1], text_size: 19, color: LOOK.color } : { width: LOOK.widths.desktop[0], max: LOOK.widths.desktop[1], radius: LOOK.radius, tint: LOOK.tint.slice(), color: LOOK.color, title_size: LOOK.title_size, text_size: LOOK.text_size }; else delete p.look;
                renderPanels(); later(); } }, p.look ? { checked: '' } : {})), document.createTextNode(' This panel has its own look')])));
            if (p.look) b.appendChild(ownLook(p));
            b.appendChild(field('Style', el('label', {}, [el('input', Object.assign({ type: 'checkbox', onchange: function () { p.clear = this.checked; renderPanels(); later(); } }, p.clear ? { checked: '' } : {})), document.createTextNode(' Text only, no glass box (like Welcome)')])));
            b.appendChild(field('Show', el('label', {}, [el('input', Object.assign({ type: 'checkbox', onchange: function () { p.show = this.checked; renderPanels(); later(); } }, p.show ? { checked: '' } : {})), document.createTextNode(' Shown on the home page')])));
            b.appendChild(el('div', { 'class': 'addrow' }, [el('button', { type: 'button', 'class': 'link', style: 'color:#b32d2e', text: 'Delete this panel', onclick: function () {
                if (PANELS.length < 2) { alert('Keep at least one panel.'); return; }
                if (confirm('Delete "' + name(p) + '"?')) { PANELS.splice(i, 1); renderPanels(); later(); } } })]));
            d.appendChild(b);
            d.addEventListener('toggle', function () { open[p.id] = d.open; if (d.open && focusId !== p.id) { focusId = p.id; later(); } });
            list.appendChild(d);
        });
        $('pcount').textContent = PANELS.filter(function (p) { return p.show; }).length + ' shown';
        renderGallery();
    }
    function move(i, dir) { var j = i + dir; if (j < 0 || j >= PANELS.length) return; var t = PANELS[i]; PANELS[i] = PANELS[j]; PANELS[j] = t; focusId = t.id; renderPanels(); later(); }
    $('padd').addEventListener('click', function () {
        var p = { id: 'p' + Math.random().toString(16).slice(2, 8), title: 'New panel', text: '', url: '', image: '', clear: false, show: true, label: '' };
        PANELS.push(p); open[p.id] = true; focusId = p.id; renderPanels(); later();
    });
    $('preset').addEventListener('click', function () { if (confirm('Replace all panels with the starting ones? (Nothing changes on the site until you Apply.)')) { PANELS = JSON.parse(JSON.stringify(DEF.panels)); open = {}; focusId = null; renderPanels(); later(); } });

    // The look of the panels
    var LOOKF = [['left', 'Panel left', 0, 60, '% of screen'], ['width', 'Panel width', 15, 100, '% of screen'], ['max', 'Widest', 250, 900, 'px'], ['radius', 'Rounded corners', 0, 40, 'px'], ['blur', 'Glass blur', 0, 30, 'px'],
        ['tint', 'Glass', 0, 95, '%'], ['border', 'Edge', 0, 100, '%'], ['shine', 'Shine', 0, 100, '%'], ['glow', 'Glow on hover', 0, 100, '%'], ['title_size', 'Title size', 14, 48, 'px'], ['text_size', 'Text size', 12, 30, 'px']];
    // Per screen (Show as): panel left, width and widest, title and text size. The rest of the look is the same on every screen.
    var PERDEV = { left: ['lefts', null], width: ['widths', 0], max: ['widths', 1], title_size: ['sizes', 0], text_size: ['sizes', 1] };
    function renderLook() {
        var b = $('lbody'); b.textContent = '';
        LOOKF.forEach(function (f) {
            var key = f[0], isTint = key === 'tint', pd = PERDEV[key], out = el('output', {}), v = function () { return isTint ? LOOK.tint[1] : pd ? (pd[1] === null ? LOOK[pd[0]][MS.dev()] : LOOK[pd[0]][MS.dev()][pd[1]]) : LOOK[key]; };
            var r = el('input', { type: 'range', min: f[2], max: f[3], value: v(), oninput: function () {
                var n = +this.value;
                if (isTint) LOOK.tint[1] = n; else if (pd) MS.targets().forEach(function (d) { if (pd[1] === null) LOOK[pd[0]][d] = n; else LOOK[pd[0]][d][pd[1]] = n; }); else LOOK[key] = n;
                out.textContent = n + ' ' + f[4]; lookChanged(); } });
            out.textContent = v() + ' ' + f[4];
            var wrap = el('div', { 'class': 'range' }, [r, out]);
            if (isTint) { wrap = el('div', { 'class': 'colour' }, [el('input', { type: 'color', value: LOOK.tint[0], oninput: function () { LOOK.tint[0] = this.value; lookChanged(); } }), wrap]); wrap.lastChild.style.flex = '1'; }
            b.appendChild(el('div', { 'class': 'row' }, [el('label', { text: pd ? f[1] + ' (' + MS.label() + ')' : f[1] }), wrap]));
        });
        b.appendChild(el('div', { 'class': 'row' }, [el('label', { text: 'Text colour' }), el('input', { type: 'color', value: LOOK.color, style: 'width:44px;height:30px;padding:0', oninput: function () { LOOK.color = this.value; lookChanged(); } })]));
        b.appendChild(el('p', { 'class': 'note' }, [el('button', { type: 'button', 'class': 'link', text: 'Back to the starting look', onclick: function () { LOOK = JSON.parse(JSON.stringify(DEF.look)); renderLook(); lookChanged(); } })]));
        $('lchip').textContent = JSON.stringify(LOOK) === JSON.stringify(DEF.look) ? 'Starting look' : 'Changed'; $('lchip').className = 'chip' + (JSON.stringify(LOOK) === JSON.stringify(DEF.look) ? '' : ' is-on');
    }
    function lookChanged() { $('lchip').textContent = JSON.stringify(LOOK) === JSON.stringify(DEF.look) ? 'Starting look' : 'Changed'; $('lchip').className = 'chip' + (JSON.stringify(LOOK) === JSON.stringify(DEF.look) ? '' : ' is-on'); MS.send(); }

    // Spotlight images: the gallery, make a new one, delete one made here
    function renderGallery() {
        var g = $('gal'); g.textContent = '';
        IMAGES.forEach(function (src) {
            var m = /\/(custom|obj)-([a-f0-9]{8})\.png$/.exec(src), custom = !!m, w = el('div', { 'class': 'gitem' }, [thumb(src, false, function () {}, custom ? ' custom' : '')]);
            if (custom) w.appendChild(el('button', { type: 'button', 'class': 'x', title: 'Delete this image', text: '×', onclick: function () { del(src); } }));
            if (m && m[1] === 'obj' && OBJECTS[m[2]]) w.appendChild(el('button', { type: 'button', 'class': 'e', title: 'Change this image (move, turn or resize the object and its shadow)', text: '✎', onclick: function () { openObj(m[2]); } }));
            g.appendChild(w);
        });
        $('icount').textContent = IMAGES.length + ' images';
    }
    function post(fd) { fd.append('csrf', CSRF); return fetch(location.pathname, { method: 'POST', body: fd, credentials: 'same-origin' }).then(function (r) { return r.json(); }); }
    $('upgo').addEventListener('click', function () {
        var f = $('upfile').files[0]; if (!f) { $('upmsg').textContent = 'Choose a picture first.'; return; }
        var fd = new FormData(); fd.append('action', 'objnew'); fd.append('picture', f); fd.append('mode', document.querySelector('input[name=objmode]:checked').value);
        $('upmsg').textContent = 'Making it…';
        post(fd).then(function (j) {
            if (j.error) { $('upmsg').textContent = j.error; return; }
            OBJECTS[j.id] = j.settings; OBJV[j.path] = Date.now(); IMAGES.unshift(j.path);
            $('upmsg').textContent = 'Made - nudge it below, or leave it as it is; it is in the lists now.'; $('upfile').value = ''; renderPanels(); openObj(j.id);
        }).catch(function () { $('upmsg').textContent = 'The upload failed.'; });
    });

    // The image editor: sliders for the object and its shadow; the preview is the real image, made on the server as you move them
    var OBJF = [['Object', null], ['scale', 'Size', 10, 300, '%'], ['dx', 'Left / right', -160, 160, 'px'], ['dy', 'Up / down', -300, 300, 'px'], ['rot', 'Turn', -180, 180, '°'],
                ['Shadow', null], ['sh_scale', 'Size', 10, 300, '%'], ['sh_dx', 'Left / right', -160, 160, 'px'], ['sh_dy', 'Up / down', -100, 100, 'px'], ['sh_rot', 'Turn', -180, 180, '°'],
                ['sh_flat', 'Flatten', 3, 100, '%'], ['sh_lean', 'Lean', -150, 150, '%']];
    var cur = null, curSet = null, prevTimer = null, prevSeq = 0;
    function objDefaults(mode) { return mode === 'standing' ? { mode: mode, scale: 100, dx: 0, dy: 0, rot: 0, sh_scale: 100, sh_dx: 0, sh_dy: 0, sh_rot: 0, sh_flat: 12, sh_lean: -20 }
                                                          : { mode: mode, scale: 100, dx: 0, dy: 0, rot: 0, sh_scale: 100, sh_dx: 0, sh_dy: 0, sh_rot: 0, sh_flat: 45, sh_lean: 0 }; }
    function objPath(id) { return 'assets/images/spotlight/obj-' + id + '.png'; }
    function buildObjControls() {
        var box = $('objctl'); box.textContent = '';
        OBJF.forEach(function (f) {
            if (f[1] === null) { box.appendChild(el('h4', { text: f[0] })); return; }
            if (f[0] === 'sh_lean' && curSet.mode !== 'standing') return; // only a standing object's shadow leans
            var key = f[0], out = el('output', { text: curSet[key] + ' ' + f[4] });
            var r = el('input', { type: 'range', min: f[2], max: f[3], value: curSet[key], oninput: function () { curSet[key] = +this.value; out.textContent = this.value + ' ' + f[4]; objPreview(); } });
            box.appendChild(el('div', { 'class': 'row' }, [el('label', { text: f[1] }), el('div', { 'class': 'range' }, [r, out])]));
        });
    }
    function openObj(id) {
        cur = id; curSet = JSON.parse(JSON.stringify(OBJECTS[id])); $('objed').hidden = false; $('objname').textContent = 'Image ' + id; $('objmsg').textContent = '';
        document.querySelectorAll('input[name=objmode2]').forEach(function (r) { r.checked = r.value === curSet.mode; });
        buildObjControls(); objPreview(true); $('objed').scrollIntoView({ block: 'nearest' });
    }
    function objPreview(now) {
        clearTimeout(prevTimer);
        prevTimer = setTimeout(function () {
            var seq = ++prevSeq, fd = new FormData(); fd.append('csrf', CSRF); fd.append('action', 'objpreview'); fd.append('id', cur); fd.append('settings', JSON.stringify(curSet));
            $('objimg').className = 'busy';
            fetch(location.pathname, { method: 'POST', body: fd, credentials: 'same-origin' }).then(function (r) { if (!r.ok) throw 0; return r.blob(); }).then(function (b) {
                if (seq !== prevSeq) return; // a newer one is on its way
                var old = $('objimg').src; $('objimg').src = URL.createObjectURL(b); $('objimg').className = ''; if (old && old.indexOf('blob:') === 0) URL.revokeObjectURL(old);
            }).catch(function () { if (seq === prevSeq) { $('objimg').className = ''; $('objmsg').textContent = 'The preview failed.'; } });
        }, now ? 0 : 120);
    }
    document.querySelectorAll('input[name=objmode2]').forEach(function (r) {
        r.addEventListener('change', function () { if (!r.checked || !cur) return; var keep = {}; ['scale', 'dx', 'dy', 'rot'].forEach(function (k) { keep[k] = curSet[k]; }); curSet = Object.assign(objDefaults(r.value), keep); buildObjControls(); objPreview(); });
    });
    $('objreset').addEventListener('click', function () { if (cur) { curSet = objDefaults(curSet.mode); buildObjControls(); objPreview(true); } });
    $('objclose').addEventListener('click', function () { cur = null; prevSeq++; $('objed').hidden = true; });
    $('objsave').addEventListener('click', function () {
        if (!cur) return;
        var fd = new FormData(); fd.append('action', 'objsave'); fd.append('id', cur); fd.append('settings', JSON.stringify(curSet)); $('objmsg').textContent = 'Saving…';
        post(fd).then(function (j) {
            if (j.error) { $('objmsg').textContent = j.error; return; }
            OBJECTS[cur] = j.settings; OBJV[j.path] = Date.now(); $('objmsg').textContent = 'Saved - panels using this image show the new one after Apply.'; renderGallery(); renderPanels();
        }).catch(function () { $('objmsg').textContent = 'The save failed.'; });
    });
    function del(src) {
        if (!confirm('Delete this image?')) return;
        var fd = new FormData(); fd.append('action', 'delimg'); fd.append('path', src);
        post(fd).then(function (j) { if (j.error) { alert(j.error + ' (Apply first if you just removed it from a panel.)'); return; } IMAGES.splice(IMAGES.indexOf(src), 1); PANELS.forEach(function (p) { if (p.image === src) p.image = ''; }); renderPanels(); });
    }

    $('form').addEventListener('submit', function () { $('panels_json').value = JSON.stringify(PANELS); $('look_json').value = JSON.stringify(LOOK); $('site_json').value = JSON.stringify(SITE); });
    renderPanels(); showDevice();
})();
</script>
</body></html>
