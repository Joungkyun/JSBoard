<?

# list paget table ratio ===================================
$td_width[1] = "5%";	# ��ȣ �ʵ� �ʺ�

# ���� �ʵ� �ʺ�
if ($upload[yesno] == "yes") $td_width[2] = "54%";
else $td_width[2] = "65%";

$td_width[3] = "14%";   # �۾��� �ʵ� �ʺ�
$td_width[4] = "9%";    # ���� �ʵ� �ʺ�
$td_width[5] = "13%";   # ��¥ �ʵ� �ʺ�
$td_width[6] = "5%";    # ������ �ʵ� �ʺ�

# read page table ratio=====================================
$readp[name] = "40%";
$readp[date] = "40%";
$readp[read] = "20%";

# menu icon�� ũ�� =========================================
$icons[size] = "20";

# write, edit, reply page form size ========================
$size[name] = form_size(14);
$size[pass] = form_size(4);
$size[titl] = form_size(25);
if ($agent[br] == "MOZL6" && $agent[os] == "LINUX") $size[text] = form_size(41);
else $size[text] = form_size(28);
$size[uplo] = form_size(19);
?>
