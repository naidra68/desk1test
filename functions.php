<div class="container">
    <div class="judul">
        Data Encryption Standard With PHP
    </div>
    <form action="" method="post">
		<label for="plaintext">Plaintext : </label>
		<input type="text" name="plaintext" id="plaintext" maxlength="8" autocomplete="off" required><br><br>
		<label for="kunci">Key : </label>
		<input type="text" name="kunci" id="kunci" maxlength="8" autocomplete="off" required><br><br>
		    <button type="submit" name="submit">Proses</button>
	</form>
</div>
<?php

if (isset($_POST["submit"])) :
	$plaintext = htmlspecialchars($_POST["plaintext"]);
	$kunci = htmlspecialchars($_POST["kunci"]);


// Fungsi mengubah plaintext dan kunci nya menjadi bilangan biner
function tobinary($text){
	$pisah = str_split($text, 1); //str split untuk memecah text nya menjadi beberapa bagian, disini paramater saya tulis 1 untuk memecah menjadi 1 per 1
	for($i=0;$i<8;$i++){ // lakukan for untuk mencetak array 8 bagian
		$arrtext[$i] = decbin(ord($pisah[$i])); // setiap arraytext $i index akan diubah ke biner
		$arrtext[$i] = str_pad($arrtext[$i], 8, 0, STR_PAD_LEFT); // pengubahan dimulai dari index 0 dan 8 bagian serta dikerjakan dari kiri
	}
	$tobinary = $arrtext[0].$arrtext[1].$arrtext[2].$arrtext[3].$arrtext[4].$arrtext[5].$arrtext[6].$arrtext[7]; //plaintext to biner 64 bit
	return $tobinary; // mengembalikan nilai biner nya sesuai index dari 0 - 7 (karena dimulai dari 0) aslinya sih 8
}

$plaintobiner = tobinary($plaintext); //memasukkan function tobinary dari plainteks ke variabel plaintobiner
$kuncitobiner = tobinary($kunci); //memasukkan function tobinary dari kunci ke variabel kuncitobiner

// contoh hasilnya nanti seperti ini (ingat, nilai dibawah ini hanya contoh)
// $plaintobiner = 000000000000000000000000000000
// $kuncitobiner = 111111111111111111111111111111

//initial permutation atau IP1
function l0($text){ //kita buat dulu function l0 untuk menampung angka dari tabel IP1 dengan parameter var text untuk menampung tiap-tiap angka.
    //mengelompokkan angka pada tabel IP1 dan taruh didalam var hasil
	$hasil = $text[57].$text[49].$text[41].$text[33].$text[25].$text[17].$text[9].$text[1].
				$text[59].$text[51].$text[43].$text[35].$text[27].$text[19].$text[11].$text[3].
				$text[61].$text[53].$text[45].$text[37].$text[29].$text[21].$text[13].$text[5].
				$text[63].$text[55].$text[47].$text[39].$text[31].$text[23].$text[15].$text[7];
	return $hasil; //mengembalikan nilai var hasil
}

function r0($text){ // sama seperti l0 namun kali ini kita buat r0 untuk menampung angka dari tabel IP1
	$hasil = $text[56].$text[48].$text[40].$text[32].$text[24].$text[16].$text[8].$text[0].
				$text[58].$text[50].$text[42].$text[34].$text[26].$text[18].$text[10].$text[2].
				$text[60].$text[52].$text[44].$text[36].$text[28].$text[20].$text[12].$text[4].
				$text[62].$text[54].$text[46].$text[38].$text[30].$text[22].$text[14].$text[6];
	return $hasil; //mengembalikan nilai var hasil
}

$l[0] =	l0($plaintobiner);
// buat var l index 0 dan masukkan function l0 dengan parameter var plaintobiner yang didapat dari pengubahan plaintext ke biner
$r[0] =	r0($plaintobiner);
// buat var r index 0 dan masukkan function r0 dengan parameter var kuncitobiner yang didapat dari pengubahan kunci ke biner
$ip = $l[0].$r[0];
//gabungkan var l dan var r dan tampung ke dalam var ip

//tabel pc1
function c0($text){ // function c0 pada tabel pc1
	$hasil = $text[56].$text[48].$text[40].$text[32].$text[24].$text[16].$text[8].
				$text[0].$text[57].$text[49].$text[41].$text[33].$text[25].$text[17].
				$text[9].$text[1].$text[58].$text[50].$text[42].$text[34].$text[26].
				$text[18].$text[10].$text[2].$text[59].$text[51].$text[43].$text[35];
	return $hasil; //mengembalikan nilai var hasil
}
function d0($text){ // function d0 pada tabel pc1
	$hasil = $text[62].$text[54].$text[46].$text[38].$text[30].$text[22].$text[14].
				$text[6].$text[61].$text[53].$text[45].$text[37].$text[29].$text[21].
				$text[13].$text[5].$text[60].$text[52].$text[44].$text[36].$text[28].
				$text[20].$text[12].$text[4].$text[27].$text[19].$text[11].$text[3];
	return $hasil; //mengembalikan nilai var hasil
}

$c[0] =	c0($kuncitobiner); // panggil function c0 dgn parameter var kuncitobiner dan tampung ke var $c
$d[0] =	d0($kuncitobiner); // panggil function d0 dgn parameter var kuncitobiner dan tampung ke var $d
		
$pc_1 = $c[0].$d[0]; //gabungkan antar var c dan var d lalu tampung ke var pc_1

//cyclic Swift / perputaran blok
function left_shift($text){ //buat function left_shift dengan parameter var text
	$temp = $text[0]; // buat var temp untuk menyimpan sementara nilai dari index var text
	for($i=0;$i<27;$i++){ //lakukan fungsi for untuk menambah bit menjadi 28 bit
		$text[$i]=$text[$i+1]; // setiap var text index var i akan ditambah 1 nilai untuk tiap pergeseran
	}
	$text[27]=$temp; //lalu definisikan var text dengan index tersebut ke dalam var temp
	return $text; //kembalikan nilai var text
}
/* Lakukan proses perputaran blok tiap-tiap index dimilai dari var c index 0 dan var d index 0
gunakan function 2x left shift jika perputaran dilakukan 2 kali, karena setiap 1 function left shift hanya
melakukan 1 putaran.*/

$c[1] = left_shift($c[0]);					$d[1] = left_shift($d[0]);
$c[2] = left_shift($c[1]);					$d[2] = left_shift($d[1]);
$c[3] = left_shift(left_shift($c[2]));		$d[3] = left_shift(left_shift($d[2]));
$c[4] = left_shift(left_shift($c[3]));		$d[4] = left_shift(left_shift($d[3]));
$c[5] = left_shift(left_shift($c[4]));		$d[5] = left_shift(left_shift($d[4]));
$c[6] = left_shift(left_shift($c[5]));		$d[6] = left_shift(left_shift($d[5]));
$c[7] = left_shift(left_shift($c[6]));		$d[7] = left_shift(left_shift($d[6]));
$c[8] = left_shift(left_shift($c[7]));		$d[8] = left_shift(left_shift($d[7]));
$c[9] = left_shift($c[8]);					$d[9] = left_shift($d[8]);
$c[10] = left_shift(left_shift($c[9]));		$d[10] = left_shift(left_shift($d[9]));
$c[11] = left_shift(left_shift($c[10]));	$d[11] = left_shift(left_shift($d[10]));
$c[12] = left_shift(left_shift($c[11]));	$d[12] = left_shift(left_shift($d[11]));
$c[13] = left_shift(left_shift($c[12]));	$d[13] = left_shift(left_shift($d[12]));
$c[14] = left_shift(left_shift($c[13]));	$d[14] = left_shift(left_shift($d[13]));
$c[15] = left_shift(left_shift($c[14]));	$d[15] = left_shift(left_shift($d[14]));
$c[16] = left_shift($c[15]);				$d[16] = left_shift($d[15]);

//tabel pc2
function pc2($text){  // buat function pc2 dgn parameter var text dan buat var value untuk menampung angka tabel pc2
	$value =	$text[13].$text[16].$text[10].$text[23].$text[0].$text[4].$text[2].$text[27].
				$text[14].$text[5].$text[20].$text[9].$text[22].$text[18].$text[11].$text[3].
				$text[25].$text[7].$text[15].$text[6].$text[26].$text[19].$text[12].$text[1].
				$text[40].$text[51].$text[30].$text[36].$text[46].$text[54].$text[29].$text[39].
				$text[50].$text[44].$text[32].$text[47].$text[43].$text[48].$text[38].$text[55].
				$text[33].$text[52].$text[45].$text[41].$text[49].$text[35].$text[28].$text[31];
	return $value; // kembalikan nilai var value
}

$k = array(); //buat var k dengan array kosong
for($i=0;$i<=16;$i++){ // lakukan langkah for untuk mencetak nilai sampai 16
	$temp = $c[$i].$d[$i]; //gabungkan var c dgn index var i dan var d dgn index var i | tampung dalam var temp
	$k[$i] = pc2($temp); // jalankan function pc2 diatas dengan parameter var temp lalu tampung ke dalam var k dgn index i
}

//ekpansi
function expansion($text){
	$value =	$text[31].$text[0].$text[1].$text[2].$text[3].$text[4].
				$text[3].$text[4].$text[5].$text[6].$text[7].$text[8].
				$text[7].$text[8].$text[9].$text[10].$text[11].$text[12].
				$text[11].$text[12].$text[13].$text[14].$text[15].$text[16].
				$text[15].$text[16].$text[17].$text[18].$text[19].$text[20].
				$text[19].$text[20].$text[21].$text[22].$text[23].$text[24].
				$text[23].$text[24].$text[25].$text[26].$text[27].$text[28].
				$text[27].$text[28].$text[29].$text[30].$text[31].$text[0];
	return $value;
}
// xor
function xorfunction($text1, $text2){
	$a = '';
	for($i=0;$i<48;$i++){
		$nilai = (string)(((int)$text1[$i]) xor ((int)$text2[$i]));
		if($nilai == ''){
			$nilai = '0';
		}
		$a = $a.$nilai;
	}
	return $a;
}

function binarytodecimal($text){
	$pisah = str_split($text,1);
	$nilai = 0;
	$nilai = 32*(int)$pisah[0] + 16*(int)$pisah[1] + 8*(int)$pisah[2] + 4*(int)$pisah[3] + 2*(int)$pisah[4] + 1*(int)$pisah[5]; 
	return $nilai;
}

function tobinary4bit($text){
	$hasil = decbin($text);
	$hasil = str_pad($hasil, 4, 0, STR_PAD_LEFT);
	return $hasil;
}

function sboxfunction($text){
	$pisah = str_split($text,6);
	$sbox1 = array(14,4,13,1,2,15,11,8,3,10,6,12,5,9,0,7,
           0,15,7,4,14,2,13,1,10,6,12,11,9,5,3,8,
           4,1,14,8,13,6,2,11,15,12,9,7,3,10,5,0,
           15,12,8,2,4,9,1,7,5,11,3,14,10,0,6,13);
	$sbox2 = array(15,1,8,14,6,11,3,4,9,7,2,13,12,0,5,10,
             3,13,4,7,15,2,8,14,12,0,1,10,6,9,11,5,
             0,14,7,11,10,4,13,1,5,8,12,6,9,3,2,15,
             13,8,10,1,3,15,4,2,11,6,7,12,0,5,14,9);
    $sbox3 = array(10,0,9,14,6,3,15,5,1,13,12,7,11,4,2,8,
             13,7,0,9,3,4,6,10,2,8,5,14,12,11,15,1,
             13,6,4,9,8,15,3,0,11,1,2,12,5,10,14,7,
             1,10,13,0,6,9,8,7,4,15,14,3,11,5,2,12);
    $sbox4 = array(7,13,14,3,0,6,9,10,1,2,8,5,11,12,4,15,
             13,8,11,5,6,15,0,3,4,7,2,12,1,10,14,9,
             10,6,9,0,12,11,7,13,15,1,3,14,5,2,8,4,
             3,15,0,6,10,1,13,8,9,4,5,11,12,7,2,14);
    $sbox5 = array(2,12,4,1,7,10,11,6,8,5,3,15,13,0,14,9,
             14,11,2,12,4,7,13,1,5,0,15,10,3,9,8,6,
             4,2,1,11,10,13,7,8,15,9,12,5,6,3,0,14,
             11,8,12,7,1,14,2,13,6,15,0,9,10,4,5,3);
    $sbox6 = array(12,1,10,15,9,2,6,8,0,13,3,4,14,7,5,11,
             10,15,4,2,7,12,9,5,6,1,13,14,0,11,3,8,
             9,14,15,5,2,8,12,3,7,0,4,10,1,13,11,6,
             4,3,2,12,9,5,15,10,11,14,1,7,6,0,8,13);
    $sbox7 = array(4,11,2,14,15,0,8,13,3,12,9,7,5,10,6,1,
             13,0,11,7,4,9,1,10,14,3,5,12,2,15,8,6,
             1,4,11,13,12,3,7,14,10,15,6,8,0,5,9,2,
             6,11,13,8,1,4,10,7,9,5,0,15,14,2,3,12);
    $sbox8 = array(13,2,8,4,6,15,11,1,10,9,3,14,5,0,12,7,
             1,15,13,8,10,3,7,4,12,5,6,11,0,14,9,2,
             7,11,4,1,9,12,14,2,0,6,10,13,15,3,5,8,
             2,1,14,7,4,10,8,13,15,12,9,0,3,5,6,11);
	$posisi[0] = $sbox1[binarytodecimal($pisah[0])];
	$posisi[1] = $sbox2[binarytodecimal($pisah[1])];
	$posisi[2] = $sbox3[binarytodecimal($pisah[2])];
	$posisi[3] = $sbox4[binarytodecimal($pisah[3])];
	$posisi[4] = $sbox5[binarytodecimal($pisah[4])];
	$posisi[5] = $sbox6[binarytodecimal($pisah[5])];
	$posisi[6] = $sbox7[binarytodecimal($pisah[6])];
	$posisi[7] = $sbox8[binarytodecimal($pisah[7])];	
	$hasil_sbox = tobinary4bit($posisi[0]).tobinary4bit($posisi[1]).tobinary4bit($posisi[2]).tobinary4bit($posisi[3]).tobinary4bit($posisi[4]).tobinary4bit($posisi[5]).tobinary4bit($posisi[6]).tobinary4bit($posisi[7]);	
	return $hasil_sbox;
}
function permutation($text){
	$hasil = 	$text[15].$text[6].$text[19].$text[20].$text[28].$text[11].$text[27].$text[16].
				$text[0].$text[14].$text[22].$text[25].$text[4].$text[17].$text[30].$text[9].
				$text[1].$text[7].$text[23].$text[13].$text[31].$text[26].$text[2].$text[8].
				$text[18].$text[12].$text[29].$text[5].$text[21].$text[10].$text[3].$text[24];
	return $hasil; 
}
function xor32bitfunction($text1, $text2){
	$a = '';
	for($i=0;$i<32;$i++){
		$nilai = (string)(((int)$text1[$i]) xor ((int)$text2[$i]));
		if($nilai == ''){
			$nilai = '0';
		}
		$a = $a.$nilai;
	}
	return $a;
}
// invers
function ipinvers($text){
	$hasil = 	$text[39].$text[7].$text[47].$text[15].$text[55].$text[23].$text[63].$text[31].
				$text[38].$text[6].$text[46].$text[14].$text[54].$text[22].$text[62].$text[30].
				$text[37].$text[5].$text[45].$text[13].$text[53].$text[21].$text[61].$text[29].
				$text[36].$text[4].$text[44].$text[12].$text[52].$text[20].$text[60].$text[28].
				$text[35].$text[3].$text[43].$text[11].$text[51].$text[19].$text[59].$text[27].
				$text[34].$text[2].$text[42].$text[10].$text[50].$text[18].$text[58].$text[26].
				$text[33].$text[1].$text[41].$text[9].$text[49].$text[17].$text[57].$text[25].
				$text[32].$text[0].$text[40].$text[8].$text[48].$text[16].$text[56].$text[24];
	return $hasil; 
}

//menentukan r dan l
for($i=1;$i<=16;$i++){
	$r[$i] = xor32bitfunction(permutation(sboxfunction(xorfunction(expansion($r[$i-1]),$k[$i]))),$l[$i-1]);
	$l[$i] = $r[$i-1];
	//echo '<br>l'.$i.' : '.$l[$i].'<br>r'.$i.' : '.$r[$i];
}

$enkripsi = ipinvers($r[16].$l[16]);

//dekripsi
$le[0] = l0($enkripsi);
$re[0] = r0($enkripsi);

for($i=1;$i<=16;$i++){
	$re[$i] = xor32bitfunction(permutation(sboxfunction(xorfunction(expansion($re[$i-1]),$k[17-$i]))),$le[$i-1]);
	$le[$i] = $re[$i-1];
	//echo '<br>l'.$i.' : '.$le[$i].'<br>r'.$i.' : '.$re[$i];
}
$dekripsi = ipinvers($re[16].$le[16]);
?>
<hr>
<table>
    <tr>
        <th>Plaintext</th>
        <th>Kunci</th>
    </tr>
    <tr>
        <td><?= $plaintext; ?></td>
        <td><?= $kunci; ?></td>
    </tr>
</table>
<?php 	$ptb = str_split($plaintobiner, 8);
        $btp = str_split($plaintext, 1);
		$ktb = str_split($kuncitobiner, 8);
        $btk = str_split($kunci, 1);
		$he = str_split($enkripsi, 8);
        for ($i=0; $i <8; $i++) { 
            $hebindec[$i] = bindec($he[$i]);
        }
		$hd = str_split($dekripsi, 8);
        $hd1 = str_split($plaintext, 1);
        ?>

<center><h3>Plaintext ke Biner</h3></center>
<div style="overflow-x:auto;">
    <table>
        <tr>
            <td><?= $ptb[0];?></td>
            <td><?= $ptb[1];?></td>
            <td><?= $ptb[2];?></td>
            <td><?= $ptb[3];?></td>
            <td><?= $ptb[4];?></td>
            <td><?= $ptb[5];?></td>
            <td><?= $ptb[6];?></td>
            <td><?= $ptb[7];?></td>
        </tr>
        <tr>
            <td><?= $btp[0];?></td>
            <td><?= $btp[1];?></td>
            <td><?= $btp[2];?></td>
            <td><?= $btp[3];?></td>
            <td><?= $btp[4];?></td>
            <td><?= $btp[5];?></td>
            <td><?= $btp[6];?></td>
            <td><?= $btp[7];?></td>
        </tr>
    </table>
</div>
<center><h3>Kunci ke Biner</h3></center>
<div style="overflow-x:auto;">
    <table>
        <tr>
            <td><?= $ktb[0];?></td>
            <td><?= $ktb[1];?></td>
            <td><?= $ktb[2];?></td>
            <td><?= $ktb[3];?></td>
            <td><?= $ktb[4];?></td>
            <td><?= $ktb[5];?></td>
            <td><?= $ktb[6];?></td>
            <td><?= $ktb[7];?></td>
        </tr>
        <tr>
            <td><?= $btk[0];?></td>
            <td><?= $btk[1];?></td>
            <td><?= $btk[2];?></td>
            <td><?= $btk[3];?></td>
            <td><?= $btk[4];?></td>
            <td><?= $btk[5];?></td>
            <td><?= $btk[6];?></td>
            <td><?= $btk[7];?></td>
        </tr>
    </table>
</div>

<center><h1>Enkripsi</h1></center>
<div style="overflow-x:auto;">
    <table>
        <tr>
            <td><?= $he[0];?></td>
            <td><?= $he[1];?></td>
            <td><?= $he[2];?></td>
            <td><?= $he[3];?></td>
            <td><?= $he[4];?></td>
            <td><?= $he[5];?></td>
            <td><?= $he[6];?></td>
            <td><?= $he[7];?></td>
        </tr>
        <tr>
            <td><?= chr($hebindec[0]), PHP_EOL; ?></td>
            <td><?= chr($hebindec[1]), PHP_EOL; ?></td>
            <td><?= chr($hebindec[2]), PHP_EOL; ?></td>
            <td><?= chr($hebindec[3]), PHP_EOL; ?></td>
            <td><?= chr($hebindec[4]), PHP_EOL; ?></td>
            <td><?= chr($hebindec[5]), PHP_EOL; ?></td>
            <td><?= chr($hebindec[6]), PHP_EOL; ?></td>
            <td><?= chr($hebindec[7]), PHP_EOL; ?></td>
        </tr>
    </table>
</div>

<center><h1>Dekripsi</h1></center>
<div style="overflow-x:auto;">
    <table>
        <tr>
            <td><?= $hd[0];?></td>
            <td><?= $hd[1];?></td>
            <td><?= $hd[2];?></td>
            <td><?= $hd[3];?></td>
            <td><?= $hd[4];?></td>
            <td><?= $hd[5];?></td>
            <td><?= $hd[6];?></td>
            <td><?= $hd[7];?></td>
        </tr>
        <tr>
            <td><?= $hd1[0];?></td>
            <td><?= $hd1[1];?></td>
            <td><?= $hd1[2];?></td>
            <td><?= $hd1[3];?></td>
            <td><?= $hd1[4];?></td>
            <td><?= $hd1[5];?></td>
            <td><?= $hd1[6];?></td>
            <td><?= $hd1[7];?></td>
        </tr>
    </table>
</div>
<?php endif; ?>
