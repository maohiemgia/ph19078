<!-- Student: tuannvph19078 -->

<!-- Bài luyện tập Lab OOP
Yêu cầu sử dụng tầm vực public, protected, private phù hợp

Bài 1:
- Xây dựng đối tượng TinhToan gồm thuộc tính số a, số b
- Interface PhepTinh gồm cộng trừ nhân chia
- Định nghĩa các phương thức và sử dụng đối tượng TinhToan để kiểm tra các phép tính

Bài 2:
- Xây dựng đối tượng ConNguoi gồm tên(string), tuổi (number), giới tính (number), ngày sinh(date), cân nặng(number), chiều cao(number)
- Xây dựng đối tượng VanDongVien kế thừa ConNguoi, 
    - có thêm thuộc tính: số huy chương(number), các môn đã thi đấu(array)
    - Có thêm phương thức: 
        - construct, 
        - hiển thị thông tin
        - thi đấu (nhận vào 2 tham số: môn thi đấu đã khởi tạo theo đối tượng bên dưới, số huy chương và kiểm tra nếu điều kiện chiều cao cân nặng không thoả mãn thì bị trừ số huy chương tương ứng đáng ra nhận được)
- Xây dựng đối tượng MonThiDau gồm tên(string), điều kiện chiều cao(number), điều kiện cân nặng(number) -->

<?php
date_default_timezone_set("Asia/Ho_Chi_Minh");

// bài 1

use ConNguoi as GlobalConNguoi;
use VanDongVien as GlobalVanDongVien;

interface PhepTinh
{
     public function sum();
     public function subtraction();
     public function multiplication();
     public function division();
}

class TinhToan implements PhepTinh
{
     public $a, $b;
     function __construct($a, $b)
     {
          $this->a = $a;
          $this->b = $b;
     }
     public function sum()
     {
          return $this->a + $this->b;
     }
     public function subtraction()
     {
          return $this->a - $this->b;
     }
     public function multiplication()
     {
          return $this->a * $this->b;
     }
     public function division()
     {
          return $this->a / $this->b;
     }
}

$tinh_toan = new TinhToan(10, 5);
echo "<h2>Bài 1: </h2><br>";
echo "The sum is: " . $tinh_toan->sum() . "<br>";
echo "The subtraction is: " . $tinh_toan->subtraction() . "<br>";
echo "The multiplication is: " . $tinh_toan->multiplication() . "<br>";
echo "The division is: " . $tinh_toan->division() . "<br>";


// bài 2
class ConNguoi
{
     public $name, $age, $gender, $birth, $weight, $height;
}

class VanDongVien extends ConNguoi
{
     public $achievement, $contest_joined = [];
     public function __construct($name, $age, $gender, $birth, $weight, $height, $achievement, $contest_joined)
     {
          $this->name = $name;
          $this->age = $age;
          $this->gender = $gender;
          $this->birth = $birth;
          $this->weight = $weight;
          $this->height = $height;
          $this->achievement = $achievement;
          if (is_array($contest_joined)) {
               array_push($this->contest_joined, ...$contest_joined);
          } else {
               array_push($this->contest_joined, $contest_joined);
          }
          $this->isWorking = 1;
     }

     public function displayInfor()
     {
          echo "VDV name: $this->name <br>";
          echo "VDV age: $this->age <br>";
          echo "VDV gender: $this->gender <br>";
          echo "VDV birth: $this->birth <br>";
          echo "VDV height: $this->height cm<br>";
          echo "VDV weight: $this->weight kg<br>";
          echo "VDV had " . $this->achievement . " achievement. <br>";
          echo "VDV had joined: <br>";
          foreach ($this->contest_joined as $k => $value) {
               echo "+) $value <br>";
          }

          if ($this->isWorking == 1) {
               echo "VDV hiện đang được thuê bởi bộ thể thao.<br>";
          } else {
               echo "VDV đã bị đuổi việc, không thể tham gia thi đấu.<br>";
          }
     }

     // kiểm tra điều kiện thi đấu
     public function contestChecking($mon_thi_dau, $achievement_number)
     {
          // kiểm tra vdv có còn được tham gia thi đấu không
          if ($this->isWorking != 1) {
               return "<br>VDV đã bị đuổi việc, không thể tham gia thi đấu.<br>";
          }

          $check_height = $this->height < $mon_thi_dau->height_conditional ? false : true;
          $check_weight = $this->weight < $mon_thi_dau->weight_conditional ? false : true;

          // nếu vdv đủ điều kiện tham gia thì nhận được hết số huy chương của cuộc thi
          if ($check_height && $check_weight) {
               $this->achievement += $achievement_number;
               array_push($this->contest_joined, $mon_thi_dau->name_contest);

               return "<br>VDV đã thỏa mãn điều kiện chiều cao và cân nặng.<br>";
          }

          // nếu vdv không đủ điều kiện tham gia thì trừ đi số huy chương đáng nhẽ được nhận
          $this->achievement -= $achievement_number;
          if ($this->achievement < 0) {
               $this->isWorking = 0;
          }
          return "<br><b><u>VDV đã không thỏa mãn điều kiện chiều cao và cân nặng!!!</u></b><br>";
     }

     // tính tuổi nghỉ hưu
     public function retirementAge($birthDay)
     {
          $now = date_create();
          $birthDay = date_create($birthDay);
          $diff = date_diff($birthDay, $now);
          $agecal = $diff->format("%r%y");

          // >= 40 tuổi hoặc vi phạm thi đấu thì cho nghỉ việc
          if ($agecal >= 40 ||  $this->isWorking == 0) {
               $this->isWorking = 0;

               return "<br>VDV hiện $agecal tuổi, đã cho nghỉ việc<br>";
          }

          return "<br>VDV hiện $agecal tuổi, vẫn đang làm việc<br>";
     }
}

class MonThiDau
{
     public $name, $height_conditional, $weight_conditional;
     public function __construct($name_contest, $height_conditional, $weight_conditional)
     {
          $this->name_contest = $name_contest;
          $this->height_conditional = $height_conditional;
          $this->weight_conditional = $weight_conditional;
     }
}

$vdv_tuan = new VanDongVien('tuannv', 22, 'male', '2010-09-26', 60, 175, 3, ['swimming', 'running', 'jumping', 'shooting']);
// $vdv_tuan = new VanDongVien('tuannv', 22, 'male', '2010-09-26', 60, 175, 3, 'shooting');
$mon_thi_dau = new MonThiDau('hacking', 175, 60);

echo "<br><br><h2>Bài 2: </h2><br>";
$vdv_tuan->displayInfor();
echo $vdv_tuan->contestChecking($mon_thi_dau, 5);
echo "<br>";
$vdv_tuan->displayInfor();
echo $vdv_tuan->retirementAge($vdv_tuan->birth);

?>

<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>ph19078</title>
     <style>
          body {
               font-size: 25px;
          }
     </style>
</head>

<body>

</body>

</html>
