var mailLangs = {
    vi: {
      hello: 'Chào <strong id="name_in_mail">${name}</strong>,',
      for_you: 'Mazii xin gửi bạn mã kích hoạt <strong>Mazii Premium</strong>',
      thanks: 'Cảm ơn bạn đã tin dùng và ủng hộ <strong>Mazii</strong>.',
      code_title: 'Mã kích hoạt <span class="time-code">${time_code}</span>',
      instruct: 'Hướng dẫn kích hoạt trong ứng dụng',
      step_1: '<strong>Bước 1:</strong> Đăng nhập tài khoản trên ứng dụng Mazii.',
      step_2: '<strong>Bước 2:</strong> Vào menu.',
      step_3: '<strong>Bước 3:</strong> Chọn Kích hoạt bằng mã/Mã kích hoạt.',
      step_4: '<strong>Bước 4:</strong> Nhập mã kích hoạt vào ô.',
      step_5: '<strong>Bước 5:</strong> Chọn kích hoạt.',
      handbook: 'Để sử dụng ứng dụng hiệu quả, bạn xem qua cẩm nang <a href="https://drive.google.com/file/d/1x1q9VPg8JRQmckFetwbhb0DvqjjiB7k6/view?usp=sharing" target="_blank" style="cursor: pointer; text-decoration: underline;">tại đây</a>. ',
      contact_title: 'Mọi thông tin chi tiết xin liên hệ hòm thư:',
      contact_email: '<i>Email: </i>support@mazii.net',
      wish_you: 'Chúc bạn học tập tốt!',
      packages: {
        '1 tháng': '1 tháng',
        '3 tháng': '3 tháng',
        '6 tháng': '6 tháng',
        '1 năm': '1 năm',
        '2 năm': '2 năm',
        'trọn đời': 'trọn đời',
        'vĩnh viễn': 'trọn đời'
      }
    },

    en: {
      hello: 'Hello <strong id="name_in_mail">${name}</strong>,',
      for_you: 'Mazii sends you the activation code for <strong>Mazii Premium</strong>',
      thanks: 'Thank you for trusting and supporting <strong>Mazii</strong>.',
      code_title: 'Activation code <span class="time-code">${time_code}</span>',
      instruct: 'Instructions to activate in the Mazii app',
      step_1: '<strong>Step 1:</strong> Log in to your account in the Mazii app.',
      step_2: '<strong>Step 2:</strong> Go to the menu.',
      step_3: '<strong>Step 3:</strong> Select Activate with code/Activation code.',
      step_4: '<strong>Step 4:</strong> Enter the activation code in the box.',
      step_5: '<strong>Step 5:</strong> Select activate.',
      handbook: 'To use the app effectively, please read the handbook <a href="https://drive.google.com/file/d/1x1q9VPg8JRQmckFetwbhb0DvqjjiB7k6/view?usp=sharing" target="_blank" style="cursor: pointer; text-decoration: underline;">here</a>.',
      contact_title: 'For more details please contact our mailbox:',
      contact_email: '<i>Email: </i>support@mazii.net',
      wish_you: 'Wish you all the best in your study!',
      packages: {
        '1 tháng': '1 month',
        '3 tháng': '3 months',
        '6 tháng': '6 months',
        '1 năm': '1 year',
        '2 năm': '2 years',
        'trọn đời': 'lifetime',
        'vĩnh viễn': 'lifetime'
      }
    },

    id: {
      hello: 'Halo <strong id="name_in_mail">${name}</strong>,',
      for_you: 'Mazii mengirim Anda kode aktivasi untuk <strong>Mazii Premium</strong>',
      thanks: 'Terima kasih telah mempercayai dan mendukung <strong>Mazii</strong>.',
      code_title: 'Kode aktivasi <span class="time-code">${time_code}</span>',
      instruct: 'Petunjuk untuk mengaktifkan di aplikasi Mazii',
      step_1: '<strong>Langkah 1:</strong> Masuk ke akun Anda di aplikasi Mazii.',
      step_2: '<strong>Langkah 2:</strong> Buka menu.',
      step_3: '<strong>Langkah 3:</strong> Pilih Aktifkan dengan kode/Kode aktivasi.',
      step_4: '<strong>Langkah 4:</strong> Masukkan kode aktivasi ke kotak.',
      step_5: '<strong>Langkah 5:</strong> Pilih aktifkan.',
      handbook: 'Untuk menggunakan aplikasi dengan efektif, silahkan baca panduan <a href="https://drive.google.com/file/d/1x1q9VPg8JRQmckFetwbhb0DvqjjiB7k6/view?usp=sharing" target="_blank" style="cursor: pointer; text-decoration: underline;">di sini</a>.',
      contact_title: 'Untuk informasi lebih lanjut silakan hubungi kotak surat kami:',
      contact_email: '<i>Email: </i>support@mazii.net',
      wish_you: 'Semoga sukses dalam belajar!',
      packages: {
        '1 tháng': '1 bulan',
        '3 tháng': '3 bulan',
        '6 tháng': '6 bulan',
        '1 năm': '1 tahun',
        '2 năm': '2 tahun',
        'trọn đời': 'seumur hidup',
        'vĩnh viễn': 'seumur hidup'
      }
    }
  }

  function updateContentMailSendCode(name, timeCode) {
    let currentLang = $(".current_lang").val();
    let dataByLang = mailLangs[currentLang];
    let transTimeCode = dataByLang.packages[timeCode];

    $("#trans_hello").html(dataByLang.hello.replace('${name}', name));
    $("#trans_for_you").html(dataByLang.for_you);
    $("#trans_thanks").html(dataByLang.thanks);
    $("#trans_code_title").html(dataByLang.code_title.replace('${time_code}', transTimeCode));
    $("#trans_instruct").html(dataByLang.instruct);
    $("#trans_step_1").html(dataByLang.step_1);
    $("#trans_step_2").html(dataByLang.step_2);
    $("#trans_step_3").html(dataByLang.step_3);
    $("#trans_step_4").html(dataByLang.step_4);
    $("#trans_step_5").html(dataByLang.step_5);
    $("#trans_handbook").html(dataByLang.handbook);
    $("#trans_contact_title").html(dataByLang.contact_title);
    $("#trans_contact_email").html(dataByLang.contact_email);
    $("#trans_wish_you").html(dataByLang.wish_you);
  };
