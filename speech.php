<?php
$kode_speak = null;

$kalimat = [
  'A' => 'Petugas Teller',
  'B' => 'Kastemer Service',
  'C' => 'Petugas Rahn',
];

$s = "SELECT a.nomor, a.kode_jenis FROM tb_antrian a
WHERE waktu >= '$today'
AND (status = 1 OR status = 2) -- sedang dilayani | sudah dilayani
ORDER BY waktu DESC LIMIT 1 ";
$q = mysqli_query($cn, $s) or die(mysqli_error($cn));
$antrian = mysqli_fetch_assoc($q);
if ($antrian) {
  $kode = $antrian['kode_jenis'];

  $sprintf = sprintf('%03d', $antrian['nomor']);
  $kode_speak = $kode_speak ?? "Antrian $kalimat[$kode]. $kode-$sprintf";
}
?>

<input type="hiddena" id="textToSpeak" value="<?= $kode_speak ?>" size="40" />

<script>
  function bicara(text) {
    if (text) {
      const utterance = new SpeechSynthesisUtterance(text);
      utterance.lang = "id-ID"; // Bahasa Indonesia
      speechSynthesis.speak(utterance);
    }
  }

  $(function() {
    setTimeout(function() {
      bicara($('#textToSpeak').val());
    }, 500); // delay sedikit

  })
</script>