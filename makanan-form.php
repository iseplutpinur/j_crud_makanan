<?php
require './config/koneksi.php';
cek_login();

// deklarasi variable pesan
$message = false;
$message_status = false;

// cek apakah ada data yang di submit
if (isset($_POST['submit'])) {
  // ambil data dan simpan ke dalam variable
  $nama = $_POST['nama'];
  $harga = $_POST['harga'];
  $deskripsi = $_POST['deskripsi'];
  $kategori = $_POST['kategori'];
  $jenis = $_POST['jenis'];
  $query = "";


  // cek apakah datanya di tambah atau di update dengan mengecek deskripsi url
  if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $query = "UPDATE makanan SET nama='$nama', deskripsi='$deskripsi', kategori_id='$kategori', jenis_id='$jenis', harga='$harga' WHERE id='$id'";
  }
  // jika tidak ada data yang di kirim di url maka data di tambah
  else {
    $query = "INSERT INTO `makanan` (`id`, `jenis_id`, `kategori_id`, `nama`, `harga`, `deskripsi`) VALUES
    (NULL, '$jenis', '$kategori', '$nama', '$harga', '$deskripsi')";
  }
  $result = mysqli_query($conn, $query);

  // buat pesan untuk menandakan query berhasil atau tidak
  $message = $result ? "Data berhasil disimpan" : "Data gagal disimpan";
  $message_status = $result;
}

$id = '';
$nama = '';
$deskripsi = '';
$kategori = '';
$jenis = '';
$harga = '';
$title = 'Tambah';
// cek jika halaman ini untuk edit data
if (isset($_GET['edit'])) {
  $id = $_GET['edit'];
  $title = 'Ubah';

  // mengambil data dari database
  $result = mysqli_query($conn, "SELECT * FROM makanan WHERE id='$id'");
  $data = mysqli_fetch_assoc($result);
  // jika data di temukan maka simpan ke dalam variable yang sudah ada.
  if ($data) {
    $nama = $data['nama'];
    $deskripsi = $data['deskripsi'];
    $harga = $data['harga'];
    $kategori = $data['kategori_id'];
    $jenis = $data['jenis_id'];
  }
}
?>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title><?= $title ?> Data Makanan | CRUD Data Makanan</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- bootstrap template -->

  <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="./index.php">CRUD Data Makanan</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item">
          <a class="nav-link" href="./index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="./jenis.php">jenis</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="./kategori.php">Kategori</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="./makanan.php">Makanan</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="./logout.php">Logout</a>
        </li>
      </ul>
    </div>
  </nav>

  <main class="container">
    <?php if ($message) : ?>
      <div class="alert alert-<?= $message_status ? 'success' : 'danger' ?> alert-dismissible fade show mt-2" role="alert">
        <strong><?= $message_status ? 'Berhasil' : 'Gagal' ?></strong> <?= $message ?>
      </div>
    <?php endif; ?>
    <div class="card shadow mt-3">
      <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
          <label class="h6"><?= $title ?> Data Makanan</label>
          <a href="./makanan.php" class="btn btn-sm btn-secondary">Kembali</a>
        </div>
      </div>
      <div class="card-body">
        <form method="POST">
          <div class="form-group">
            <label for="nama">Nama Makanan</label>
            <input type="text" class="form-control" name="nama" id="nama" value="<?= $nama ?>" placeholder="Nama Makanan" required>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="jenis">jenis</label>
                <select class="form-control" name="jenis" id="jenis">
                  <?php
                  $result = mysqli_query($conn, "SELECT * FROM jenis");
                  while ($row = mysqli_fetch_assoc($result)) {
                    $selected = $row['id'] == $jenis ? 'selected' : '';
                    echo "<option value='{$row['id']}' {$selected}>{$row['nama']}</option>";
                  }
                  ?>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="kategori">Kategori</label>
                <select class="form-control" name="kategori" id="kategori">
                  <?php
                  $result = mysqli_query($conn, "SELECT * FROM kategori");
                  while ($row = mysqli_fetch_assoc($result)) {
                    $selected = $row['id'] == $kategori ? 'selected' : '';
                    echo "<option value='{$row['id']}' {$selected}>{$row['nama']}</option>";
                  }
                  ?>
                </select>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label for="harga">Harga Terbit</label>
            <input type="number" class="form-control" name="harga" id="harga" value="<?= $harga ?>" placeholder="Harga Terbit" required>
          </div>
          <div class="form-group">
            <label for="deskripsi">Deskripsi</label>
            <textarea class="form-control" name="deskripsi" id="deskripsi" rows="3"><?= $deskripsi ?></textarea>
          </div>
          <button type="submit" name="submit" class="btn btn-primary" title="Simpan data">Simpan</button>
        </form>
      </div>
    </div>
  </main>

  <div class="footer bg-dark text-light py-3 mt-3">
    <div class="container">
      <p class="m-0">Copyright &copy 2022 | Fakhirah Azzahra (2113201050)</p>
    </div>
  </div>

  <script src="./bootstrap/jquery-3.6.0.js"></script>
  <script src="./bootstrap/js/bootstrap.min.js"></script>
</body>

</html>