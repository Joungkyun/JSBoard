<?

// list paget table ratio ===================================
$td_width[1] = "6%";	// 번호 필드 너비

// 제목 필드 너비
if ($upload[yesno] == "yes") $td_width[2] = "54%";
else $td_width[2] = "62%";

$td_width[3] = "12%";   // 글쓴이 필드 너비
$td_width[4] = "10%";    // 파일 필드 너비
$td_width[5] = "13%";   // 날짜 필드 너비
$td_width[6] = "5%";    // 읽은수 필드 너비


// read page table ratio=====================================
$readp[name] = "40%";
$readp[date] = "40%";
$readp[read] = "20%";

// menu icon의 크기 =========================================
$icons[size] = "20";

// write, edit, reply page form size ========================
$size[name] = form_size(14);
$size[pass] = form_size(4);
$size[titl] = form_size(25);
$size[text] = form_size(30);
$size[uplo] = form_size(19);

?>
