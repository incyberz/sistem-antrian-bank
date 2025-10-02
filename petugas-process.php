<?php
if (isset($_POST['btn_next_antrian'])) {
  $t = explode('--', $_POST['btn_next_antrian']);
  $id_sedang_dilayani = $t[0]; // boleh null, saat awal
  $id_next_sisa = $t[1] ?? null; // boleh null, untuk update antrian sebelumnya saja
  $status_selesai = $t[2] ?? stop("invalid index-ke-2 untuk status_selesai antrian pada Petugas Processor.");
  if (!($status_selesai == 2 || $status_selesai == -1)) {
    stop("invalid nilai status_selesai [$status_selesai]");
  }

  if ($id_sedang_dilayani) {
    # ============================================================
    # UPDATE SELESAI DILAYANI
    # ============================================================
    $s = "UPDATE tb_antrian SET 
    akhir_layanan = CURRENT_TIMESTAMP,
    petugas = '$username',
    status = $status_selesai -- complete(2) | skipped(-1) 
    WHERE id = $id_sedang_dilayani
    ";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    // alert("Update akhir layanan sebelumnya sukses.", 'success');
  }



  if ($id_next_sisa) {
    # ============================================================
    # UPDATE SISA ANTRIAN
    # ============================================================
    $s = "UPDATE tb_antrian SET 
    awal_layanan = CURRENT_TIMESTAMP,
    status = 1 -- sedang dilayani 
    WHERE id = $id_next_sisa
    ";
    $q = mysqli_query($cn, $s) or die(mysqli_error($cn));
    // alert("Update awal layanan antrian baru sukses.", 'success');
  } else {
    // alert("Saat ini belum ada Nasabah baru untuk Petugas $user[jenis_role].", 'info');
  }
  // echo "<div class=p-2><a class='btn btn-primary w-100' href='?'>Back to Home</a>";
  // jsurl('', 5000);
  jsurl();
  echo '</div>';
}
