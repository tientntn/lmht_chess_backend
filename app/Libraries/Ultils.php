<?php namespace App\Libraries;

  class Ultils {

    //convert validateion error to string return api for app display
    public static function validationToString($validator, $syntax = '<br/>'){
      $errors = $validator->messages()->toArray();
      $data = array_flatten($errors);
      $string = implode($data);
      return $string;
    }

    public static function getNameByKey($key) {
      $data = Ultils::getData();
      return isset($data[$key]) ? $data[$key] : $key;
    }

    public static function getKeyByName($key) {
      $data = Ultils::getData();
      $data = array_flip($data);
      return isset($data[$key]) ? $data[$key] : $key;
    }

    public static function calAgeFromBirthday($birthday) {
        $birthday = str_replace('-', '/', $birthday);
        $birthDate = explode("/", $birthday);
        if (count($birthDate) != 3) return '';
        if ($birthDate[0] == '' || $birthDate[1] == '' || $birthDate[2] == '' ) return '';
  //get age from date or birthdate
        $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md")
            ? ((date("Y") - $birthDate[2]) - 1)
            : (date("Y") - $birthDate[2]));
        return $age;
    }

    public static function getData() {
        return $data = [
        'can-bo-nha-nuoc' => 'Cán bộ nhà nước',
        'lanh-dao-quan-li' => 'Lãnh đạo/quản lí',
        'nhan-vien-cap-cao' => 'Nhân viên cấp cao',
        'nhan-vien-bac-trung' => 'Nhân viên bậc trung',
        'nhan-vien-hanh-chinh' => 'Nhân viên hành chính',
        'dich-vu-bao-hiem-sales' => 'Dịch vụ, bảo hiểm, sales',
        'nong-lam-nghiep-thuy-san' => 'Nông-lâm nghiệp, thủy sản',
        'thu-cong-nghiep' => 'Thủ công nghiệp',
        'lao-dong-khong-tay-nghe' => 'Lao động không tay nghề',
        'cong-nhan-xuong-va-nha-may' => 'Công nhân xưởng và nhà máy',
        'hoc-sinh' => 'Học sinh',
        'noi-tro' => 'Nội trợ',
        'benh-tat-khuyet-tat' => 'Bệnh tật/Khuyết tật',
        'gia-ve-huu' => 'Gìa/Về hưu',
        'gia-ve-huu' => 'Già/Về hưu',
        'that-nghiep-khong-li-do' => 'Thất nghiệp (không lí do)',
        'khac' => 'Khác',
        'csyt-tu' => 'CSYT tư',
        'csyt-cong' => 'CSYT công',
        'cong-dong-csd' => 'Cộng đồng/CSĐ',
        'tu-den' => 'Tự đến',
        'binh-thuong' => 'Bình thường',
        'bat-thuong' => 'Bất thường',
        'khong-chup-x-quang' => 'Không chụp X-quang',
        'xet-nghiem-vi-khuan-hoc' => 'Xét nghiệm vi khuẩn học',
        'chan-doan-lam-sang' => 'Chẩn đoán bằng lâm sàng',
        'soi-dam' => 'Soi đàm',
        'soi-dom' => 'Soi đàm',
        'genexpert' => 'GeneXpert',
        'nuoi-cay' => 'Nuôi cấy',
        'am-tinh' => 'Âm tính',
        'duong-1' => '+1',
        'duong-2' => '+2',
        'duong-3' => '+3',
        'co-vk-lao-khong-khang-rifampicin' => 'Có VK lao/không kháng Rifampicin',
        'co-vk-lao-khang-rifampicin' => 'Có VK lao/kháng Rifampicin',
        'mtb-khong-xac-dinh-rif' => 'Có VK lao/không xác định kháng Rifampicin',
        'khong-biet-loi' => 'Lỗi',
        'am' => 'Âm',
        'duong' => 'Dương',
        'mtb' => 'Dương tính, MTB',
        'ntm' => 'Dương tính, NTM',
        'duong-mtb' => 'Dương tính, MTB',
        'duong-ntm' => 'Dương tính, NTM',
        'ngoai-nhiem' => 'Ngoại nhiễm',
        'lao-phoi' => 'Phổi',
        'lao-phoi-ngoai' => 'Ngoài phổi',
        'co' => 'Có',
        'khong' => 'Không',
        'hain' => 'Hain',
        'h' => 'H',
        'r' => 'R',
        's' => 'S',
        'emb' => 'EMB',
        'pza' => 'PZA',
        'km' => 'Km',
        'pto' => 'Pto',
        'cs' => 'Cs',
        'pas' => 'PAS',
        'moi' => 'Mới',
        'tai-phat' => 'Tái phát',
        'that-bai' => 'Thất bại',
        'dieu-tri-sau-bo-tri' => 'Điều trị sau bỏ trị',
        'tien-su-dieu-tri-khac' => 'Tiền sử điều trị khác',
        'khong-ro-tien-su-dieu-tri' => 'Không rõ tiền sử điều trị',
        '2rhze-4rhe' => '2RHZE/4RHE',
        '2srhze-4rhe' => '2SRHZE/4RHE ',
        '2rhze-4rh' => '2RHZE/4RH',
        '2srhze-1rhze-5rhe' => '2SRHZE/1RHZE/5RHE',
        '2srhze-1rhze-5r3h3e3' => '2SRHZE/1RHZE/5R3H3E3',
        '2rhze-10rhe' => '2RHZE/10RHE',
        '2rhze-10rh' => '2RHZE/10RH',
        'moi' => 'Mới',
        'tai-phat' => 'Tái phát',
        'khac' => 'Khác',
        'that-bai-phac-do-i' => 'Thất bại phác đồ I',
        'that-bai-phac-do-ii' => 'Thất bại phác đồ II',
        'dieu-tri-lai-sau-bo-tri' => 'Điều trị lại sau bỏ trị',
        'tram-y-te' => 'Trạm y tế',
        'tcl' => 'TCL',
        'bv-da-khoa' => 'BV đa khoa',
        'bv-lao-phoi' => 'BV lao phổi',
        'khac' => 'Khác',
        'igra' => 'IGRA',
        'tst' => 'TST',
        'khong-phan-ung' => 'Không phản ứng',
        '5-mm' => '5 mm',
        '10-mm' => '10 mm',
        '15-mm' => '15 mm',
        'duong-tinh' => 'Dương tính',
        'khong-xac-dinh' => 'Không xác định',
        'qft-git' => 'QFT-GIT',
        't-spot' => 'T-spot',
        'duong-bien-phan-dinh' => 'Đường biên phân định',
        'csyt-tu-nhan' => 'CSYT tư nhân',
        'csyt-cong-khac' => 'CSYT công khác',
        'dieu-tri-danh-cho-lao-thuong' => 'Điều trị dành cho lao thường',
        'dieu-tri-danh-cho-lao-khang' => 'Điều trị dành cho lao kháng',
        'xet-nghiem-vi-khuan-hoc' => 'Xét nghiệm vi khuẩn học',
        'chan-doan-bang-lam-san' => 'Chẩn đoán bằng lâm sàng',
        'so-luong-afb-it' => 'Số lượng AFB (ít)',
        'so-luong-afb-it' => 'Số lượng AFB(ít)',
        'khoi' => 'Khỏi',
        'hoan-thanh-dieu-tri' => 'Hoàn thành điều trị',
        'that-bai' => 'Thất bại',
        'khong-theo-doi-duoc' => 'Không theo dõi được',
        'khong-danh-gia' => 'Không đánh giá',
        'tu-vong' => 'Tử vong',
        'thuong_tru_kt1' => 'Thường trú (KT1)',
        'tam_tru_dai_han_trong_tinh_kt2' => 'Tạm trú dài hạn trong phạm vi tỉnh (KT2)',
        'tam_tru_dai_dan_tinhthanh_pho_khac_noi_thuong_tru_kt3' => 'Tạm trú dài hạn ở tỉnh/thành phố khác với nơi đăng ký thường trú (KT3)',
        'tam_tru_ngan_han_o_tinhthanh_pho_khac_noi_thuong_tru_kt4' => 'Tạm trú ngắn hạn ở tỉnh/thành phố khác với nơi đăng ký thường trú (KT4)',
        'nguoi_tiep_xuc_hgd' => 'Người tiếp xúc HGĐ (sống cùng nhà với BN lao ít nhất 2 đêm/tuần trong 6 tháng qua)',
        'nguoi_tiep_xuc_cong_dong' => 'Người tiếp xúc cộng đồng (Tiếp xúc với BN lao 1 tiếng/tuần trong tháng qua nhưng không phải là NTX HGĐ)',
        'nguoi_co_nguy_co_cao' => 'Người có nguy cơ cao (Người không phải là NTX HGĐ hay NTX cộng đồng, nhưng có triệu chứng nghi lao)',
        'bat-thuong-nghi-lao' => 'Bất thường nghi lao',
        'khong-biet-khong-xet-nghiem' => 'Không biết /không xét nghiệm',
        'khong_di_hoc'          => 'Không đi học',
        'hoc_chua_het_tieu_hoc' => 'Học hết tiểu học',
        'hoc_het_tieu_hoc'          => 'Học hết trung học',
        'hoc_chua_het_trung_hoc'          => 'Học hết trung học phổ thông',
        'truong_nghe'          => 'Trường nghề',
        'cao_dang'          => 'Cao đẳng',
        'dai_hoccao_hoc'          => 'Đại học/ Cao học',
        'khong_du_tien_di_den_tram_xa' => 'Không đủ tiền đi đến trạm xá',
        'khong_du_tien_chup_xquang' => 'Không đủ tiền chụp X-Quang',
        'khong_nghi_rang_minh_can_di_xet_nghiem' => 'Không nghĩ rằng mình cần đi xét',
        'quen_khong_di_xet_nghiem' => 'Quên không đi xét nghiệm',
        'khong_muon_biet_minh_bi_lao_hay_khong' => 'Không muốn biết mình bị lao hay không',
        'khong_chi_tra_duoc_cho_cac_dich_vu_y_te_khac' => 'Không chi trả được cho các dịch vụ y tế khác',
        'gio_hoat_dong_cua_phong_kham_khong_thuan_tien' => 'Giờ hoạt động của phòng khám không thuận tiện',
        'khong_nghi_viec_de_di_xet_nghiem_duoc' => 'Không nghỉ việc để đi xét nghiệm được',
        'phong_kham_gan_nhat_qua_xa_nha' => 'Phòng khám gần nhất quá xa nhà',
        'qua_met_nen_khong_di_duoc' => 'Quá mệt nên không đi được',
        'da_duoc_dieu_tri_lao' => 'Đã được điều trị Lao',
        'da_co_phim_xquang_va_khong_muon_chup_them' => 'Đã có phim Xquang và không muốn chụp thêm',
        'ly_do_khac_khong_duoc_liet_ke_o_tren' => 'Lý do khác (không được liệt kê ở trên)',
        'nguoi_than' => 'Người thân',
        'ban_be' => 'Bạn bè',
        'ban_cung_lop' => 'Bạn cùng lớp',
        'dong_nghiep' => 'Đồng nghiệp',
        'nguoi_cung_cap_dich_vu' => 'Người cung cấp dịch vụ',
        'hang_xom' => 'Hàng xóm',
        'chan-doan-lam-sang' => 'Chẩn đoán lâm sàng',
        'he-thong-ctcl'      => 'Hệ thống CTCL',
        'y-te-cong-tham-gia-ctcl' => 'Y tế công tham gia CTCL',
        'bat-thuong-khong-nghi-lao' => 'Bất thường không nghi lao',
        'duong-1' => '1+',
        'duong-2' => '2+',
        'duong-3' => '3+',
        'benh_nhan_lao_cu' => 'Bệnh nhân lao cũ',
        'nguoi_60_tuoi_co_benh_dong_mac' => 'Người cao tuổi ≥  60 tuổi có bệnh đồng mắc',
        'nguoi_cao_tuoi_75_tuoi' => 'Người cao tuổi ≥  60 tuổi sống trong hộ nghèo',
        'doi_tuong_chinh_sach' => 'Người cao tuổi ≥  75 tuổi',
        'nhom_khac' => 'Nhóm khác',
        'reason_duplicate' => 'Trùng bệnh nhân từ VITIMES',
        'reason_contact_info_not_complete' => 'Thông tin liên hệ từ VITIMES chưa hoàn chỉnh hoặc không chính xác',
        'reason_could_not_contact' => 'Không thể liên hệ sau nhiều lần thử',
        'reason_patient_refused' => 'Bệnh nhân từ chối',
        'reason_two_patient_in_household' => 'Đây là bệnh nhân thứ 2 trong hộ gia đình và đã đến thăm',
        'reason_no_ctv_assigned' => 'Không có CTV / TVV nào ở phường này',
        'reason_other' => 'Khác',
        'patient_not_return' => 'Bệnh nhân không trở lại đọc TST',
        'hoan_thanh' => 'Hoàn thành',
        'mat_dau' => 'Mất dấu',
        'tu_vong' => 'Tử vong',
        'chuyen_di' => 'Chuyển đi',
        'phan_ung_phu' => 'Phản ứng phụ',
        'lao_tien_trien' => 'Lao tiến triển',
      ];
    }


  }
?>