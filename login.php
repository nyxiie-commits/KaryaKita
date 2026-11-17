<?php require 'functions.php';if(user())go('index.php');$err='';$attemptKey=hash('sha256', strtolower((string)($_SERVER['REMOTE_ADDR']??'unknown')));if($_SERVER['REQUEST_METHOD']==='POST'){if(login_is_limited($attemptKey)){$err='Terlalu banyak percobaan masuk. Coba lagi dalam 15 menit.';}else{$email=strtolower(trim($_POST['email']??''));$pass=$_POST['password']??'';$s=$pdo->prepare('SELECT * FROM users WHERE email=?');$s->execute([$email]);$u=$s->fetch();if($u&&password_verify($pass,$u['password'])){login_succeeded($attemptKey);session_regenerate_id(true);$_SESSION['user']=['id'=>$u['id'],'name'=>$u['name'],'email'=>$u['email'],'role'=>$u['role']];$dest=$_SESSION['after_login']??($u['role']==='admin'?'admin/index.php':'index.php');unset($_SESSION['after_login']);header('Location: '.$dest);exit;}else{login_failed($attemptKey);$err='Email atau password salah.';}}}$pageTitle='Masuk';require 'header.php';?><section class="auth"><h1>Selamat datang kembali</h1><p>Masuk untuk melanjutkan belanja di KaryaKita.</p><?php if($err):?><div class="notice error"><?=e($err)?></div><?php endif;?><form method="post" class="form-card"><?=csrf_field()?><label>Email<input type="email" name="email" required></label><label>Password
<div class="password-field">
<input id="loginPassword" type="password" name="password" required autocomplete="current-password">
<button class="password-toggle" type="button" id="togglePassword" aria-label="Tampilkan password" aria-pressed="false">
<svg id="eyeIcon" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
</button>
</div>
</label>
<style>
.password-field{position:relative;display:flex;align-items:center;width:100%}
.password-field input{width:100%;padding-right:48px!important}
.password-toggle{position:absolute;right:5px;top:50%;transform:translateY(-50%);width:38px;height:38px;border:0;background:transparent;color:#765b3e;display:grid;place-items:center;cursor:pointer;border-radius:8px}
.password-toggle:hover{background:#fff3d2}
.password-toggle:focus-visible{outline:2px solid #e99435;outline-offset:1px}
</style>
<script>
(function(){
 const field=document.getElementById('loginPassword');
 const toggle=document.getElementById('togglePassword');
 const icon=document.getElementById('eyeIcon');
 if(!field||!toggle||!icon)return;
 toggle.addEventListener('click',function(){
   const show=field.type==='password';
   field.type=show?'text':'password';
   toggle.setAttribute('aria-pressed',show?'true':'false');
   toggle.setAttribute('aria-label',show?'Sembunyikan password':'Tampilkan password');
   icon.innerHTML=show
    ? '<path d="M3 3l18 18"/><path d="M10.6 10.6a2 2 0 0 0 2.8 2.8"/><path d="M9.9 5.2A10.8 10.8 0 0 1 12 5c6.4 0 10 7 10 7a17 17 0 0 1-3.1 3.9"/><path d="M6.2 6.2C3.5 8 2 12 2 12s3.6 7 10 7c1.3 0 2.5-.3 3.5-.8"/>'
    : '<path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/>';
 });
})();
</script><button class="btn">Masuk</button></form><p>Belum punya akun? <a href="<?=url('register.php')?>">Daftar pelanggan</a></p></section><?php require 'footer.php';?>