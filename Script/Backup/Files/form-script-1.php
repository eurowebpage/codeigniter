<div class="row">
 <div class="file-backup">
<!-- <form action="< ?= base_url('AdminController/downloadFileBackup'); ?>" method="post"> -->
<form action="<?= base_url('AdminController/backup'); ?>" method="post">
 <?= csrf_field(); ?> 
<!-- <button type="submit" class="btn btn-block btn-success"><i class="fa fa-download"></i>&nbsp;&nbsp; File Backup</button> -->
<input type="text" name="zip" hidden value="zip">
<input type="hidden" name="back_url" value="<?= getCurrentUrl(); ?>">
<button type="submit" class="btn btn-block btn-success"><i class="fa fa-download"></i>&nbsp;&nbsp; Create Files Backup</button>

</form>
   
</div>
<?php
if(isset($_POST["zip"]) && $_POST["zip"] == "zip" ){
$redirurl = $_POST["back_url"];
$zip_file_name = uuid4("",".zip");
$zip_path = uuid4(FCPATH,".zip");
date_default_timezone_set('Europe/Brussels');
$date_save = '-'.date('d-m-Y-H-i-s');
$nomdns = $_SERVER['SERVER_NAME'];
//$the_folder = './'; 
$the_folder = FCPATH;
//$zip_file_name = $nomdns.$date_save."2.zip";
$download_file= true;
$delete_file_after_download= true; 
if ($delete_file_after_download == true) 
{ 
if(file_exists($zip_path)){
unlink($zip_path);

}
}
class FlxZipArchive extends ZipArchive {
public function addDir($location, $name) {
$this->addEmptyDir($name);
$this->addDirDo($location, $name);
} 
private function addDirDo($location, $name) {
$name .= '/';
$location .= '/';
// Read all Files in Dir
$dir = opendir ($location);
while ($file = readdir($dir))
{
if ($file == '.' || $file == '..') continue;
// Rekursiv, If dir: FlxZipArchive::addDir(), else ::File();
$do = (filetype( $location . $file) == 'dir') ? 'addDir' : 'addFile';
$this->$do($location . $file, $name . $file);
}
} // EO addDirDo();
}
$za = new FlxZipArchive;
$res = $za->open($zip_file_name, ZipArchive::CREATE);
if($res === TRUE) 
{
$za->addDir($the_folder, basename($the_folder));
$za->close();
}
else{ echo 'Could not create a zip archive';}
redirectToUrl($redirurl);
//exit();
}
?>
