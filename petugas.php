<?php
# ============================================================
# GET SISA ANTRIAN HARI INI
# ============================================================
$s = "SELECT * FROM tb_antrian a 
WHERE waktu >= '$today' 
AND kode_jenis = '$user[kode_role]'
AND status is null -- SISA ANTRIAN
ORDER BY nomor 
";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$rsisa_count = mysqli_num_rows($q);

$rsisa = [];
$next_sisa = [];
if ($rsisa_count) {
  while ($d = mysqli_fetch_assoc($q)) {
    // hanya rows pertama yang diproses
    $next_sisa = $next_sisa ? $next_sisa : $d;
    $rsisa[$d['id']] = $d;
  }
}

# ============================================================
# NEXT ANTRIAN PROCESS
# ============================================================
include 'petugas-process.php';


# ============================================================
# ANTRIAN YANG SEDANG DILAYANI
# ============================================================
$s = "SELECT a.id, a.nomor FROM tb_antrian a 
WHERE waktu >= '$today' 
AND kode_jenis = '$user[kode_role]'
AND status = 1 -- sedang dilayani
ORDER BY waktu DESC LIMIT 1 ";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$antrian_sedang = mysqli_fetch_assoc($q);
if (!mysqli_num_rows($q)) {
  $antrian_sedang['nomor'] = 0;
  $antrian_sedang['id'] = '';
}

# ============================================================
# UI BERDASARKAN NEXT SISA
# ============================================================
if (!$rsisa) {
  // $disabled = 'disabled'; // harus bisa di klik untuk update akhir layanan
  $info = 'belum ada antrian selanjutnya';
  $disabled = '';
} else {
  $disabled = '';
  $info = "terdapat $rsisa_count sisa antrian $user[jenis_role]";
}

# ============================================================
# TOTAL ANTRIAN
# ============================================================
include 'total_antrian.php';
$info .= "<span class=hideit>, total antrian: <span id=total_antrian>$total_antrian</span></span>";

# ============================================================
# UI BERDASARKAN ANTRIAN SEDANG
# ============================================================
$btn_value = $next_sisa ? "$antrian_sedang[id]--$next_sisa[id]" : "$antrian_sedang[id]--";
if ($antrian_sedang['id']) {
  $disabled_skip = '';
} else {
  $disabled_skip = 'disabled';
}


$nomor_show = $antrian_sedang['nomor'] ? sprintf('%03d', $antrian_sedang['nomor']) : '---';
$nomor_show = "$user[kode_role]$nomor_show";
?>
<link rel="stylesheet" href="css/petugas.css">
<div class="petugas ">
  <img src="img/logo.png" alt="logo" class="logo" />
  <div class="role-label">ANTRIAN <?= $user['jenis_role'] ?></div>
  <div class="antrian-saat-ini">Saat Ini</div>
  <div class="antrian-saat-ini-nomor"><?= $nomor_show ?></div>
  <form method=post class="form-next-antrian">
    <div class="row">
      <div class="col-6">
        <button
          name=btn_next_antrian
          value="<?= $btn_value ?>---1"
          class="btn-next-antrian btn btn-danger w-100"
          <?= $disabled_skip ?>
          onclick="return confirm(`Skip Antrian?`)">
          <span class="span-next-antrian">Skip Antrian</span>
        </button>
      </div>
      <div class="col-6">
        <button
          name=btn_next_antrian
          value="<?= $btn_value ?>--2"
          class="btn-next-antrian btn btn-primary w-100"
          <?= $disabled ?>>
          <span class="span-next-antrian">Next Antrian</span>
        </button>
      </div>
    </div>
    <div class="btn-info abu"><?= $info ?></div>
  </form>

</div>

<script src="js/update_counts.js"></script>