<?php
$displays = '';
$marquess = '';

foreach ($rjenis_antrian as $kode => $d) {
  if ($d['status'] != 1) continue;
  $s = "SELECT a.nomor FROM tb_antrian a 
  WHERE waktu >= '$today' 
  AND kode_jenis = '$kode'
  AND (status = 1 OR status = 2) -- sedang dilayani | sudah dilayani
  ORDER BY waktu DESC LIMIT 1 ";
  $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
  $antrian = mysqli_fetch_assoc($q);
  if ($antrian) {
    $sprintf = sprintf('%03d', $antrian['nomor']);
    $kode_nomor = "$kode$sprintf";
  } else {
    $kode_nomor = "----";
  }

  $bg = strtolower("bg-$d[singkatan]");

  $displays .= "
    <div class='row-display $bg'>
      <div class='row'>
        <div class=col-6>
          <div class='d-flex justify-content-center align-items-center h-100'>
            <div class='jenis-antrian'>$d[singkatan]</div>
          </div>
        </div>
        <div class=col-6>
          <div class='display display-$kode'>$kode_nomor</div>
        </div>
      </div>
    </div>
  ";
}

# ============================================================
# TOTAL ANTRIAN | REALTIME COUNTS | YANG SEDANG DAN SUDAH DILAYANI
# ============================================================
$jenis_antrian = 'terlayani'; // custom parameter pada total_antrian.php
include 'total_antrian.php';


?>
<link rel="stylesheet" href="css/display.css">
<span id="nama-page" class="nama-page">display</span>
<div class="text-center">
  <div class="sedang-dipanggil-header">SEDANG DIPANGGIL</div>
  <div class="blok-displays bg-debugs">
    <?= $displays ?>
  </div>
  <div class='total-antrian'>
    <div class="hideit">
      Total antrian hari ini: <span id=total_antrian><?= $total_antrian ?></span>
    </div>
    <?php include 'marquee.php'; ?>
  </div>

</div>

<script src="js/update_counts.js"></script>
<?php include 'speech.php'; ?>