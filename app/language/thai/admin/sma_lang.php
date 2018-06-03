<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Module: General Language File for common lang keys
 * Language: Thai
 *
 * Last edited:
 * 30th April 2015
 *
 * Package:
 * Stock Manage Advance v3.0
 *
 * You can translate this file to your language.
 * For instruction on new language setup, please visit the documentations.
 * You also can share your language files by emailing to saleem@tecdiary.com
 * Thank you
 */

/* --------------------- CUSTOM FIELDS ------------------------ */
/*
* Below are custome field labels
* Please only change the part after = and make sure you change the the words in between "";
* $lang['bcf1']                         = "Biller Custom Field 1";
* Don't change this                     = "You can change this part";
* For support email contact@tecdiary.com Thank you!
*/

$lang['bcf1']                           = "Biller Custom Field 1";
$lang['bcf2']                           = "Biller Custom Field 2";
$lang['bcf3']                           = "Biller Custom Field 3";
$lang['bcf4']                           = "Biller Custom Field 4";
$lang['bcf5']                           = "Biller Custom Field 5";
$lang['bcf6']                           = "Biller Custom Field 6";
$lang['pcf1']                           = "Product Custom Field 1";
$lang['pcf2']                           = "Product Custom Field 2";
$lang['pcf3']                           = "Product Custom Field 3";
$lang['pcf4']                           = "Product Custom Field 4";
$lang['pcf5']                           = "Product Custom Field 5";
$lang['pcf6']                           = "Product Custom Field 6";
$lang['ccf1']                           = "Customer Custom Field 1";
$lang['ccf2']                           = "Customer Custom Field 2";
$lang['ccf3']                           = "Customer Custom Field 3";
$lang['ccf4']                           = "Customer Custom Field 4";
$lang['ccf5']                           = "Customer Custom Field 5";
$lang['ccf6']                           = "Customer Custom Field 6";
$lang['scf1']                           = "Supplier Custom Field 1";
$lang['scf2']                           = "Supplier Custom Field 2";
$lang['scf3']                           = "Supplier Custom Field 3";
$lang['scf4']                           = "Supplier Custom Field 4";
$lang['scf5']                           = "Supplier Custom Field 5";
$lang['scf6']                           = "Supplier Custom Field 6";

/* ----------------- DATATABLES LANGUAGE ---------------------- */
/*
* Below are datatables language entries
* Please only change the part after = and make sure you change the the words in between "";
* 'sEmptyTable'                     => "No data available in table",
* Don't change this                 => "You can change this part but not the word between and ending with _ like _START_;
* For support email support@tecdiary.com Thank you!
*/

$lang['datatables_lang']        = array(
    'sEmptyTable'                   => "No data available in table",
    'sInfo'                         => "Showing _START_ to _END_ of _TOTAL_ entries",
    'sInfoEmpty'                    => "Showing 0 to 0 of 0 entries",
    'sInfoFiltered'                 => "(filtered from _MAX_ total entries)",
    'sInfoPostFix'                  => "",
    'sInfoThousands'                => ",",
    'sLengthMenu'                   => "Show _MENU_ ",
    'sLoadingRecords'               => "Loading...",
    'sProcessing'                   => "Processing...",
    'sSearch'                       => "Search",
    'sZeroRecords'                  => "No matching records found",
    'oAria'                                     => array(
      'sSortAscending'                => ": activate to sort column ascending",
      'sSortDescending'               => ": activate to sort column descending"
      ),
    'oPaginate'                                 => array(
      'sFirst'                        => "<< First",
      'sLast'                         => "Last >>",
      'sNext'                         => "Next >",
      'sPrevious'                     => "< Previous",
      )
    );

/* ----------------- Select2 LANGUAGE ---------------------- */
/*
* Below are select2 lib language entries
* Please only change the part after = and make sure you change the the words in between "";
* 's2_errorLoading'                 => "The results could not be loaded",
* Don't change this                 => "You can change this part but not the word between {} like {t};
* For support email support@tecdiary.com Thank you!
*/

$lang['select2_lang']               = array(
    'formatMatches_s'               => "One result is available, press enter to select it.",
    'formatMatches_p'               => "results are available, use up and down arrow keys to navigate.",
    'formatNoMatches'               => "No matches found",
    'formatInputTooShort'           => "Please type {n} or more characters",
    'formatInputTooLong_s'          => "Please delete {n} character",
    'formatInputTooLong_p'          => "Please delete {n} characters",
    'formatSelectionTooBig_s'       => "You can only select {n} item",
    'formatSelectionTooBig_p'       => "You can only select {n} items",
    'formatLoadMore'                => "Loading more results...",
    'formatAjaxError'               => "Ajax request failed",
    'formatSearching'               => "Searching..."
    );


/* ----------------- SMA GENERAL LANGUAGE KEYS -------------------- */

$lang['home']                               = "หน้าแรก";
$lang['dashboard']                          = "แดชบอร์ด";
$lang['username']                           = "ชื่อล๊อกอิน";
$lang['password']                           = "รหัสผ่าน";
$lang['first_name']                         = "ชื่อ";
$lang['last_name']                          = "นามสกุล";
$lang['confirm_password']                   = "ยืนยันรหัสผ่าน";
$lang['email']                              = "อีเมลล์";
$lang['phone']                              = "โทรศัพท์";
$lang['company']                            = "บริษัท";
$lang['product_code']                       = "รหัสสินค้า";
$lang['product_name']                       = "ชื่อสินค้า";
$lang['cname']                              = "ชื่อลูกค้า";
$lang['barcode_symbology']                  = "บาร์โค๊ด";
$lang['product_unit']                       = "หน่วยสินค้า";
$lang['product_price']                      = "ราคาสินค้า";
$lang['contact_person']                     = "คนที่ติดต่อ";
$lang['email_address']                      = "อีเมลล์";
$lang['address']                            = "ที่อยู่";
$lang['city']                               = "จังหวัด";
$lang['today']                              = "วันนี้";
$lang['welcome']                            = "ยินดีต้อนรับ";
$lang['profile']                            = "ข้อมูลส่วนตัว";
$lang['change_password']                    = "เปลี่ยนรหัสผ่าน";
$lang['logout']                             = "ออกจากระบบ";
$lang['notifications']                      = "แจ้งข่าวสาร";
$lang['calendar']                           = "ปฏิทิน";
$lang['messages']                           = "ข้อความ";
$lang['styles']                             = "รูปแบบ";
$lang['language']                           = "ภาษา";
$lang['alerts']                             = "การแจ้งเตือน";
$lang['list_products']                      = "รายการสินค้า";
$lang['add_product']                        = "เพิ่มสินค้า";
$lang['print_barcodes']                     = "พิมพ์บาร์โค๊ด";
$lang['print_labels']                       = "พิมพ์ตาราง";
$lang['import_products']                    = "นำเข้าข้อมูลสินค้า";
$lang['update_price']                       = "ปรับปรุงราคา";
$lang['damage_products']                    = "สินค้าชำรุด";
$lang['sales']                              = "ขายสินค้า";
$lang['list_sales']                         = "รายการขาย";
$lang['add_sale']                           = "เพิ่มการขาย";
$lang['deliveries']                         = "จัดส่งสินค้า";
$lang['gift_cards']                         = "กิฟท์การ์ด";
$lang['quotes']                             = "ใบเสนอราคา";
$lang['list_quotes']                        = "รายการใบเสนอราคา";
$lang['add_quote']                          = "เพิ่มใบเสนอราคา";
$lang['purchases']                          = "สั่งซื้อสินค้า";
$lang['list_purchases']                     = "รายสั่งซื้อสินค้า";
$lang['add_purchase']                       = "เพิ่มสั่งซื้อสินค้า";
$lang['add_purchase_by_csv']                = "เพิ่มสั่งซื้อสินค้าโดยไฟล์ CSV";
$lang['transfers']                          = "การโอนย้ายสินค้า";
$lang['list_transfers']                     = "รายการโอนย้ายสินค้า";
$lang['add_transfer']                       = "เพิ่มการโอนย้ายสินค้า";
$lang['add_transfer_by_csv']                = "เพิ่มการโอนย้ายสินค้าโดยไฟล์ CSV";
$lang['people']                             = "ข้อมูลตัวแทน";
$lang['list_users']                         = "รายชื่อตัวแทนทั้งหมด";
$lang['new_user']                           = "เพิ่มพนักงาน";
$lang['list_billers']                       = "รายการออกบิลโดยตัวแทน";
$lang['add_biller']                         = "เพิ่มผู้ออกบิล";
$lang['list_customers']                     = "รายการลูกค้าทั้งหมด";
$lang['add_customer']                       = "เพิ่มลูกค้า";
$lang['list_suppliers']                     = "รายการผู้ผลิตสินค้าทั้งหมด";
$lang['add_supplier']                       = "ผู้ผลิตสินค้า";
$lang['settings']                           = "ตั้งค่าระบบ";
$lang['system_settings']                    = "ตั้งค่าระบบ";
$lang['change_logo']                        = "เปลี่ยนโลโก้";
$lang['currencies']                         = "สกุลเงิน";
$lang['attributes']                         = "คุณลักษณะ";
$lang['customer_groups']                    = "กลุ่มลูกค้า";
$lang['categories']                         = "หมวดหมู่";
$lang['subcategories']                      = "หมวดหมู่ย่อย";
$lang['tax_rates']                          = "อัตราภาษี";
$lang['warehouses']                         = "คลังสินค้า";
$lang['email_templates']                    = "รูปแบบอีเมลล์";
$lang['group_permissions']                  = "สิทธิ์ ของกลุ่ม";
$lang['backup_database']                    = "สำรองข้อมูล";
$lang['reports']                            = "รายงาน";
$lang['overview_chart']                     = "กราฟภาพรวม";
$lang['warehouse_stock']                    = "กราฟคลังสินค้า";
$lang['product_quantity_alerts']            = "การแจ้งเตือนจำนวนสินค้า";
$lang['product_expiry_alerts']              = "การแจ้งเตือนวันหมดอายุสินค้า";
$lang['products_report']                    = "รายงานสินค้า";
$lang['daily_sales']                        = "ยอดขายรายวัน";
$lang['monthly_sales']                      = "ยอดขายรายเดือน";
$lang['sales_report']                       = "รายงานการสั่งซื้อ";
$lang['payments_report']                    = "การเงิน";
$lang['profit_and_loss']                    = "กำไร/ขาดทุน";
$lang['purchases_report']                   = "รายงานการซื้อ";
$lang['customers_report']                   = "รายงานลูกค้า";
$lang['suppliers_report']                   = "รายงานข้อมูลผู้ผลิตสินค้า";
$lang['staff_report']                       = "รายงานพนักงาน";
$lang['your_ip']                            = "IP ของคุณ";
$lang['last_login_at']                      = "เข้าสู่ระบบครั้งสุดท้ายเมื่อ";
$lang['notification_post_at']               = "แจ้งเตือนเมื่อ";
$lang['quick_links']                        = "ลิ้งค์ที่ใช้บ่อย";
$lang['date']                               = "วัน/เวลา";
$lang['reference_no']                       = "เลขที่ใบสั่งซื้อ";
$lang['products']                           = "สินค้า";
$lang['customers']                          = "ข้อมูลลูกค้า";
$lang['suppliers']                          = "ข้อมูลผู้ผลิตสินค้า";
$lang['users']                              = "จัดการผู้ใช้งาน";
$lang['latest_five']                        = "5 รายการล่าสุด";
$lang['total']                              = "รวม";
$lang['payment_status']                     = "สถานะชำระเงิน";
//$lang['paid']                               = "เรียบร้อย";
$lang['paid']                               = "ชำระเงินแล้ว";
$lang['customer']                           = "ลูกค้า";
$lang['status']                             = "สถานะ";
$lang['amount']                             = "จำนวนเงิน";
$lang['supplier']                           = "ข้อมูลผู้ผลิตสินค้า";
$lang['from']                               = "จาก";
$lang['to']                                 = "ถึง";
$lang['name']                               = "ชื่อ";
$lang['create_user']                        = "เพิ่มข้อมูลพนักงาน";
$lang['gender']                             = "เพศ";
$lang['biller']                             = "ขายโดย";
$lang['select']                             = "เลือก";
$lang['warehouse']                          = "คลังสินค้า";
$lang['active']                             = "ทำงานอยู่";
$lang['inactive']                           = "ไม่ทำงาน";
$lang['all']                                = "ทั้งหมด";
$lang['list_results']                       = "กรุณา ใช้ตาราง ด้านล่างนี้เพื่อ นำทาง หรือกรอง ผลการค้นหา. คุณสามารถดาวโหลดตารางเป็นไฟล์ excel และ pdf.";
$lang['actions']                            = "จัดการ";
$lang['pos']                                = "POS";
$lang['access_denied']                      = "ปฏิเสธการเข้าใช้! คุณไม่ได้ มีสิทธิที่จะ เข้าถึงหน้าเว็บ ที่ร้องขอ. หากคุณคิดว่า มันเป็น ความผิดพลาด โปรด ติดต่อ ผู้ดูแลระบบ.";
$lang['add']                                = "เพิ่ม";
$lang['edit']                               = "แก้ไข";
$lang['delete']                             = "ลบ";
$lang['view']                               = "ดู";
$lang['update']                             = "ปรับปรุง";
$lang['save']                               = "บันทึก";
$lang['login']                              = "ล๊อกอิน";
$lang['submit']                             = "ยืนยัน";
$lang['no']                                 = "เลขที่";
$lang['yes']                                = "ใช่";
$lang['disable']                            = "ปิดใช้งาน";
$lang['enable']                             = "เปิดใช้งาน";
$lang['enter_info']                         = "กรุณากรอกข้อมูลด้านล่าง ช่องที่มีเครื่องหมาย * จำเป็นต้องใส่.";
$lang['update_info']                        = "โปรดอัปเดต ข้อมูลด้านล่าง ช่องที่มีเครื่องหมาย * จำเป็นต้องใส่.";
$lang['no_suggestions']                     = "ไม่สามารถ ที่จะได้รับ ข้อมูล ข้อเสนอแนะ โปรดตรวจสอบการ ป้อนข้อมูลของคุณ";
$lang['i_m_sure']                           = 'ใช่ \'ฉันมั่นใจ';
$lang['r_u_sure']                           = 'คุณแน่ใจ?';
$lang['export_to_excel']                    = "ส่งออกเป็นไฟล์   Excel";
$lang['export_to_pdf']                      = "ส่งออกเป็นไฟล์  PDF";
$lang['image']                              = "รูปภาพ";
$lang['sale']                               = "ขาย";
$lang['quote']                              = "ใบเสนอราคา";
$lang['purchase']                           = "ใบสั่งซื้้อ";
$lang['transfer']                           = "โอนสินค้า";
$lang['payment']                            = "รายการชำระเงิน";
$lang['payments']                           = "รายการชำระเงินทั้งหมด";
$lang['orders']                             = "คำสั่งซื้อสินค้า";
$lang['pdf']                                = "PDF";
$lang['vat_no']                             = "เลขประจำตัวผู้เสียภาษี";
$lang['country']                            = "ประเทศ";
$lang['add_user']                           = "เพิ่มผู้ใช้";
$lang['type']                               = "ประเภท";
$lang['person']                             = "ผู้ใช้งาน";
$lang['state']                              = "รัฐ";
$lang['postal_code']                        = "รหัสไปรษณีย์";
$lang['id']                                 = "ไอดี";
$lang['close']                              = "ปิด";
$lang['male']                               = "เพศชาย";
$lang['female']                             = "เพศหญิง";
$lang['notify_user']                        = "แจ้งผู้ใช้";
$lang['notify_user_by_email']               = "แจ้งผู้ใช้ทางอีเมลล์";
$lang['billers']                            = "ผู้ออกบิล";
$lang['all_warehouses']                     = "คลังสินค้าทั้งหมด";
$lang['category']                           = "หมวดหมู่";
$lang['product_cost']                       = "ต้นทุนสินค้า";
$lang['quantity']                           = "จำนวน";
$lang['loading_data_from_server']           = "โหลดข้อมูลจากเซอร์เวอร์";
$lang['excel']                              = "Excel";
$lang['print']                              = "พิมพ์";
$lang['ajax_error']                         = "Ajax เกิดข้อผิดพลาด,โปรดลองอีกครั้ง.";
$lang['product_tax']                        = "ภาษีสินค้า ";
$lang['order_tax']                          = "ภาษีคำสั่งซื้อ";
$lang['upload_file']                        = "อัพโหลด ไฟล์";
$lang['download_sample_file']               = "ดาวโหลดตัวอย่างไฟล์";
$lang['csv1']                               = "บรรทัดแรก ใน การดาวน์โหลด ไฟล์ CSV ควรจะอยู่อย่างที่มันเป็น กรุณาอย่า เปลี่ยนลำดับ ของคอลัมน์.";
$lang['csv2']                               = "ลำดับคอลัมน์ที่ถูกต้อง";
$lang['csv3']                               = "&amp; คุณต้องทำตามนี้. ถ้าคุณกำลังใช้ ภาษาอื่น ๆ , โปรดตรวจสอบ ไฟล์ CSV เป็น UTF- 8 เท่านั้น และ ไม่ได้ถูกบันทึก ที่มีเครื่องหมาย (BOM)";
$lang['import']                             = "นำเข้า";
$lang['note']                               = "หมายเหตุ";
$lang['grand_total']                        = "ราคารวม";
$lang['download_pdf']                       = "ดาวโหลดเป็น PDF";
$lang['no_zero_required']                   = "เขตข้อมูล%";
$lang['no_product_found']                   = "ไม่พบสินค้า";
$lang['pending']                            = "รอตรวจสอบ";
$lang['sent']                               = "ส่ง";
$lang['completed']                          = "เรียบร้อย";
$lang['shipping']                           = "ค่าขนส่ง";
$lang['add_product_to_order']               = "เพิ่มสินค้าในคำสั่งซื้อ";
$lang['order_items']                        = "รายการสินค้า";
$lang['net_unit_cost']                      = "ราคาทุน";
$lang['net_unit_price']                     = "ราคา";
$lang['expiry_date']                        = "วันหมดอายุ";
$lang['subtotal']                           = "ยอดรวม";
$lang['paid_total']                         = "ยอดชำระ";

$lang['reset']                              = "เริ่มใหม่";
$lang['items']                              = "รายการสินค้า";
$lang['au_pr_name_tip']                     = "กรุณา เริ่มพิมพ์ รหัส / ชื่อ เพื่อขอคำแนะนำ หรือเพียงแค่ สแกน บาร์โค้ด";
$lang['no_match_found']                     = "ผล การจับคู่ ไม่พบสินค้า ! สินค้าอาจจะ หมดสต็อก ในคลังสินค้า ที่เลือก.";
$lang['csv_file']                           = "CSV ไฟล์";
$lang['document']                           = "เอกสารแนบ";
$lang['product']                            = "สินค้า";
$lang['account_users']                      = "ตัวแทน";
$lang['user']                               = "ผู้ใช้";
$lang['created_by']                         = "จัดทำโดย";
$lang['loading_data']                       = "กำลังโหลด ข้อมูลตาราง จากเซิร์ฟเวอร์";
$lang['tel']                                = "เบอร์โทรศัพท์";
$lang['ref']                                = "อ้างอิง";
$lang['description']                        = "คำอธิบาย";
$lang['code']                               = "รหัส";
$lang['tax']                                = "ภาษี";
$lang['unit_price']                         = "ราคา/ชิ้น";
$lang['discount']                           = "ส่วนลด";
$lang['order_discount']                     = "ส่วนลด";
$lang['total_amount']                       = "รวมทั้งหมด";
$lang['download_excel']                     = "Download Excel";
$lang['subject']                            = "เรื่อง";
$lang['cc']                                 = "CC";
$lang['bcc']                                = "BCC";
$lang['message']                            = "ข้อความ";
$lang['show_bcc']                           = "แสดง/ซอน BCC";
$lang['price']                              = "ราคา";
$lang['add_product_manually']               = "เพิ่มสินค้าแบบกำหนดเอง";
$lang['currency']                           = "สกุลเงิน";
$lang['product_discount']                   = "สินค้าลดราคา";
$lang['email_sent']                         = "ส่งอีเมลล์ เรียบร้อยแล้ว";
$lang['add_event']                          = "เพิ่มกิจกรรมา";
$lang['add_modify_event']                   = "เพิ่ม / เพิ่มเติม กิจกรรม";
$lang['adding']                             = "กำลังเพิ่ม...";
$lang['delete']                             = "ลบ";
$lang['deleting']                           = "กำลังลบ...";
$lang['calendar_line']                      = "โปรดคลิกเพิ่มวันที่สำหรับกิจกรรม.";
$lang['discount_label']                     = "ส่วนลด (5/5%)";
$lang['product_expiry']                     = "สินค้า_วันหมดอายุ";
$lang['unit']                               = "จำนวน";
$lang['cost']                               = "ต้นทุน";
$lang['tax_method']                         = "วิธีคำนวนภาษี";
$lang['inclusive']                          = "รวม";
$lang['exclusive']                          = "สิทธิพิเศษเพียงผู้เดียว";
$lang['expiry']                             = "วันหมดอายุ";
$lang['customer_group']                     = "กลุ่มลูกค้า";
$lang['is_required']                        = "จำเป็น";
$lang['form_action']                        = "ฟอร์มการดำเนินการ";
$lang['return_sales']                       = "ส่งคืนการขาย";
$lang['list_return_sales']                  = "รายยการส่งคืนการขาย";
$lang['no_data_available']                  = "ไม่มีข้อมูลที่สามารถใช้งานได้";
$lang['disabled_in_demo']                   = "เราต้องขออภัยที่ คุณลักษณะนี้ ถูกปิดใช้งาน ในการใช้งานแบบสาธิต.";
$lang['payment_reference_no']               = "อ้างอิงการชำระเงิน";
$lang['gift_card_no']                       = "เลขที่กิฟท์การ์ด";
$lang['paying_by']                          = "จ่ายเงินโดย";
$lang['cash']                               = "เงินสด";
$lang['gift_card']                          = "กิฟท์การ์ด";
$lang['CC']                                 = "บัตรเครดิต";
$lang['cheque']                             = "เช็ค";
$lang['cc_no']                              = "หมายเลขบัตรเครดิต";
$lang['cc_holder']                          = "ชื่อเจ้าของ";
$lang['card_type']                          = "ประเภทของบัตร";
$lang['Visa']                               = "วีซ่า";
$lang['MasterCard']                         = "มาสเตอร์การ์ด";
$lang['Amex']                               = "เอเม็กซ์";
$lang['Discover']                           = "Discover";
$lang['month']                              = "เดือน";
$lang['year']                               = "ปี";
$lang['cvv2']                               = "CVV2";
$lang['cheque_no']                          = "เช็คเลขที่";
$lang['Visa']                               = "Visa";
$lang['MasterCard']                         = "MasterCard";
$lang['Amex']                               = "Amex";
$lang['Discover']                           = "Discover";
$lang['send_email']                         = "ส่่งอีเมลล์";
$lang['order_by']                           = "สั่งซื้อโดย";
$lang['updated_by']                         = "ปรับปรุงโดย";
$lang['update_at']                          = "ปรับปรุงเมื่อ";
$lang['error_404']                          = "404 ไม่พบเพจ ";
$lang['default_customer_group']             = "กลุ่มลูกค้า";
$lang['pos_settings']                       = "ตั้งค่า POS";
$lang['pos_sales']                          = "รายการขาย POS";
$lang['seller']                             = "ผู้ขาย";
$lang['ip:']                                = "ไอพี:";
$lang['sp_tax']                             = "มูลค่าภาษีขายสินค้า";
$lang['pp_tax']                             = "มูลค่าภาษีสินค้า";
$lang['overview_chart_heading']             = "ภาพรวม สต็อก รวมทั้ง ยอดขายรายเดือน ที่มีภาษี และ ภาษี การสั่งซื้อ ( คอลัมน์ ), ใบสั่งซื้อ(line) และมูลค่าสต็อกกับราคาต้นทุน (pie). คุณสามารถบันทึกเป็น jpg, png และ pdf.";
$lang['stock_value']                        = "มูลค่าสต็อก";
$lang['stock_value_by_price']               = "มูลค่าคลัง โดย ราคา";
$lang['stock_value_by_cost']                = "มูลค่าคลัง โดย ต้นทุน";
$lang['sold']                               = "ถูกขายไป";
$lang['purchased']                          = "สั่งซื้อ";
$lang['chart_lable_toggle']                 = "คุณสามารถเปลี่ยนแผนภูมิ โดย  โดยคลิกที่แผนภูมิ.คลิกเพื่อแสดง/ซ่อนแผนภูมิ.";
$lang['register_report']                    = "รายงานการลงทะเบียน";
$lang['sEmptyTable']                        = "ไม่มีข้อมูลในตาราง";
$lang['upcoming_events']                    = "กิจกรรมที่กำลังจะเกิดขึ้น";
$lang['clear_ls']                           = "ล้างข้อมูลที่เซฟ";
$lang['clear']                              = "ล้าง";
$lang['edit_order_discount']                = "แก้ไขส่วนลด";
$lang['product_variant']                    = "ความหลากหลายของสินค้า";
$lang['product_variants']                   = "ความหลากหลายของสินค้าทั้งหมด";
$lang['prduct_not_found']                   = "ไม่พบสินค้า";
$lang['list_open_registers']                = "รายการลงทะเบียน";
$lang['delivery']                           = "การจัดส่ง";
$lang['serial_no']                          = "Serial No";
$lang['logo']                               = "โลโก้";
$lang['attachment']                         = "ไฟล์แนบ";
$lang['balance']                            = "คงเหลือ";
$lang['nothing_found']                      = "ไม่พบข้อมูบที่ตรงกัน";
$lang['db_restored']                        = "ประสบความสำเร็จใน การเรียกคืน ฐานข้อมูล.";
$lang['backups']                            = "สำรองข้อมูล";
$lang['best_seller']                        = "สินค้าขายดี";
$lang['chart']                              = "แผนภูมิ";
$lang['received']                           = "ได้รับ";
$lang['returned']                           = "การส่งคืน";
$lang['award_points']                       = 'คะแนนสะสมรวม Point Reward';
$lang['expenses']                           = "ค่าใช้จ่าย";
$lang['add_expense']                        = "เพิ่มค่าใช้จ่าย";
$lang['other']                              = "อื่นๆ";
$lang['none']                               = "ไม่มี";
$lang['calculator']                         = "เครื่องคิดเลข";
$lang['updates']                            = "ปรับปรุง";
$lang['update_available']                   = "อัพเดทตอนนี้.";
$lang['please_select_customer_warehouse']   = "โปรดเลือกลูกค้า/คลังสินค้า";
$lang['variants']                           = "ประเภทที่หลากหลาย";
$lang['add_sale_by_csv']                    = "เพิ่มการขาย โดย Excel";
$lang['categories_report']                  = "รายงานหมวดหมู่สินค้า";
$lang['adjust_quantity']                    = "ปรับจำนวน สินค้า";
$lang['quantity_adjustments']               = "ปรับจำนวนสินค้าคงคลัง";
$lang['partial']                            = "บางส่วน";
$lang['unexpected_value']                   = "มูลค่าที่ไม่คาดคิด!";
$lang['select_above']                       = "โปรดเลือกให้ครบถ้วน";
$lang['no_user_selected']                   = "ยังไม่ได้เลือกผู้ใช้งาน,โปรดเลือกผู้ใช้งานอย่างน้อยหนึ่งชื่อ";
$lang['due']                                = "มีการแก้ไข";
$lang['ordered']                            = "คำสั่งซื้อสินค้า";
$lang['profit']                             = "กำไร";
$lang['unit_and_net_tip']                   = "จากการคำนวณ ในหน่วย ( ที่มีภาษี ) สุทธิ (ไม่รวม ภาษี )<strong>จำนวน(net)</strong> สำหรับการขายทั้งหมด";
$lang['expiry_alerts']                      = "การแจ้งเตือนหมดอายุ";
$lang['quantity_alerts']                    = "การแจ้งเตือนจำนวน";
$lang['products_sale']                      = "สินค้า' รายได้";
$lang['products_cost']                      = "สินค้า' ต้นทุน";
$lang['day_profit']                         = "กำไรต่อวัน/หรือ ขาดทุน";
$lang['get_day_profit']                     = "คุณสามารถคลิกที่ วัน ที่จะได้รับ ผลกำไร วัน และ / หรือรายงาน การสูญเสีย.";
$lang['print_barcode_label']                = "พิมพ์บาร์โค๊ด / พิมพ์ตาราง";
$lang['list_gift_cards']                    = "รายการกิฟท์การ์ด";

$lang['combine_to_pdf']                     = "รวมเป็น PDF";
$lang['select_language'] 					= "เลือกภาษา";
$lang['payment_confirm']                    = "แจ้งชำระเงิน";

$lang['Navigation']                    		= "เมนู";
$lang['Account']                    		= "บัญชีของฉัน";


$lang['please_select_these_before_adding_product'] = "กรุณาเลือกสิ่งเหล่านี้ก่อนเพิ่มสินค้า";
$lang['price_group']                        = "กลุ่มราคา";
$lang['price_groups']                       = "กลุ่มราคาทั้งหมด";
$lang['expenses_report']                    = "รายงานค่าใช้จ่าย";
$lang['monthly_purchases']                  = "การซื้อรายเดือน";
$lang['daily_purchases']                    = "การซื้อรายวัน";
$lang['brands_report']                      = "รายงานแบรนด์";
$lang['adjustments_report']                 = "รายงานการปรับคลังสินค้า";
$lang['variant']                            = "ความหลากหลาย";
$lang['best_sellers']                       = "สินค้าขายดี";
$lang['list_expenses']                      = "รายการค่าใช้จ่าย";
$lang['count_stock']                        = "นับจำนวนสินค้า";
$lang['stock_counts']                       = "นับจำนวนสินค้าทั้งหมด";
$lang["Today's Earnings"]                   = "ยอดขายวันนี้";
$lang["Last Yesterday"]                   	= "ยอดขายเมื่อวาน";
$lang["Amount Order Today"]                 = "จำนวนออเดอร์วันนี้";

$lang["Sales this month"]					= "ยอดขายเดือนนี้";
$lang["Last month's sales"]					= "ยอดขายเดือนที่แล้ว";
$lang["Orders this month"]					= "จำนวนออเดอร์เดือนนี้";

$lang["Shipments today"]					= "จำนวนการจัดส่งวันนี้";
$lang["Shipments yesterday"]				= "จำนวนการจัดส่งเมื่อวาน";
$lang["Shipments this month"]				= "จำนวนการจัดส่งเดือนนี้";

$lang["Visits Today"]						= "จำนวนลูกค้าวันนี้";
$lang["Totol Visit"]						= "จำนวนลูกค้าทั้งหมด";
$lang["New Visits"]							= "จำนวนลูกค้าที่สั่งซื้อซ้ำ";
$lang["unit"]                               = "หน่วยนับ";

$lang["Average_Orders"]                     = "ยอดขายเฉลี่ย ต่อออเดอร์ ";
$lang['wait']                          		= "รอดำเนินการ";
$lang['sign_in']                          	= "เข้าสู่ระบบ";
$lang['this_sale']                          = "คะแนนสะสมสำหรับออเดอร์นี้";

$lang['commission']							= "ค่าคอมมิชชั่น";
$lang['shipping_method']					= "ตั้งค่าค่าขนส่ง";

$lang['ordering']							= "การจัดเรียง";
$lang['condition']							= "เงื่อนไข";
$lang['condition_from_value']				= "เงื่อนไขจาก";
$lang['condition_to_value']					= "เงื่อนไขถึง";
$lang['delivery_type']						= "รูปแบบขนส่ง";
$lang['add_shipping']						= "เพิ่มค่าขนส่ง";
$lang['edit_shipping']						= "แก้ไขค่าขนส่ง";
$lang['delete_shipping']					= "ลบค่าขนส่ง";

$lang['ems']								= "EMS";
$lang['kerry']								= "Kerry (COD)";
$lang['rate(%)']							= "เรท(%)";
$lang['bank_account']                   	= "บัญชีธนาคาร";
$lang['balance_commission']					= "รวมค่าคอมมิชชั่น";
$lang['commission_report']					= "รายงานคอมมิชชั่น";
$lang['type_commission']					= "รูปแบบคอมมิชชั่น";
$lang['staff']								= "พนักงาน";
$lang['facebook']							= "Facebook";
$lang['line']								= "Line ID";
$lang['instragram']							= "Instragram";
$lang['tracking_daily']						= "รายงานการจัดส่ง";
$lang['tracking_data']						= "ข้อมูลรหัสพัสดุ";
$lang['tracking_day']						= "วันส่งพัสดุ";
$lang['select_all']							= "เลือกทั้งหมด";
$lang['code_billers']						= "รหัสผู้ออกบิล";
$lang['name_lastname']						= " ชื่อ-นามสกุล";
$lang['no_data']							= " ไม่มีรายการ";
$lang['add_adjustment']                     = "เพิ่มจำนวนสินค้า";
$lang['approve_wallet']                     = "อนุมัติ wallet";

 
$lang['disapprove']							= "ไม่อนุมัติ";
$lang['add_award_points']					= "เพิ่มแต้มสะสม";
$lang['approve_award_points']				= "อนุมัติ แต้มสะสม";

$lang['ship_for_price']						= "คิดค่าส่งตามราคา";
$lang['ship_for_item']						= "คิดค่าส่งตามจำนวนชิ้น";
$lang['ship_for_weight']					= "คิดค่าส่งตามน้ำหนัก";
$lang['weight_total']						= "รวมน้ำหนัก";


$lang['PickUp']								= "รับเองที่บริษัท";
$lang['pickup']								= "รับเองที่บริษัท";
$lang['more_information']                   = "ข้อมูลเพิ่มเติม";

$lang['search']								= "ค้นหา";
$lang['Add_Customer']						= "เพิ่มลูกค้า";

$lang['Add_Order']							= "เพิ่มการขาย";
$lang['add_order']							= "เพิ่มการขาย";


$lang['ship_to']							= "ชื่อผู้รับ";
$lang['Pending_tracking']					= "รอรหัสพัสดุ";
$lang['Pending_order']						= "รอตรวจสอบ";

$lang['customer']							= "ลูกค้า";
$lang['shipping_title']						= "รูปแบบขนส่ง";
$lang['ems']								= "EMS";
$lang['kerry']								= "Kerry (COD)";


$lang['select_order_type']					= "เลือกรูปแบบคำสั่งซื้อ";
$lang['select_order_status']				= "เลือกสถานะคำสั่งซื้อ";
$lang['remove']								= "เปลี่ยนใหม่";
$lang['submit_sale']						= "ยืนยันคำสั่งซื้อ";
$lang['reset']								= "เริ่มต้นใหม่";
$lang['shipment']							= "วิธีการส่งสินค้า";
$lang['payment_method']						= "วิธีการชำระเงิน";	

$lang['suggestion']							= "คำแนะนำ";	


$lang['Addressee_(Sticker)']				= "ใบจ่าหน้าพัสดุแบบสติกเกอร์";
$lang['Send_by_Agent_(A4)']					= "ส่งโดยตัวแทน (A4)";
$lang['Submitted_by_Company_(A4)']			= "ใบจ่าหน้าพัสดุแบบ A4";

$lang['cash']                   			= "โอนเงินเข้าบัญชี";
$lang['wallet']                           	= "ชำระด้วย Wallet";
$lang['wallet_summary'] 					= "ยอดเงินคงเหลือในบัญชีของคุณคือ";

$lang['click_here']							= "คลิ๊กที่นี่";
$lang['your_award_points']					= "แต้มสะสมปัจจุบัน";
$lang['point']								= "คะแนน";

$lang['owner_receiver']                   	= "พิมพ์โดยเจ้าของร้าน";
$lang['sales_receiver']                   	= "พิมพ์โดยตัวแทน";
$lang['your_account_balance_is_not_sufficient_please_refill']	=	"ยอดเงินคงเหลือในบัญชีของคุณไม่เพียงพอ กรุณาเติมเงิน";
$lang['Add item no need to specify product name or product code, press SPACE BAR to display all products list.'] = "คุณสามารถกด Space Bar หรือปุ่ม + เพื่อแสดงรายการสินค้า";	

$lang['bank_to']                    		= "โอนเข้าบัญชี";
$lang['bank_from']                    		= "จากธนาคาร";
$lang['browse']                    			= "เรียกดู";
$lang['Remove']                    			= "ลบ";


$lang['ready']                    			= "พร้อมจัดส่ง";
$lang['packing']                    		= "กำลังแพ็ค";
$lang['delivering']                    		= "จัดส่งแล้ว";
$lang['delivered']                    		= "เรียบร้อยแล้ว";
$lang['tracking']                    		= "รหัสพัสดุ";
$lang['complete']                    		= "จัดส่งเรียบร้อยแล้ว";


$lang['print & packing']                    = "พิมพ์ & แพ็ค";
$lang['time']                    			= "เวลา *";
$lang['(Ex. 50)']  							= "(ตัวอย่าง 50)";
$lang['(formate 23:59)']    				= "(รูปแบบ 23:59)";

$lang['Bangkok Bank'] 						= "ธนาคารกรุงเทพ";
$lang['Krungthai Bank'] 					= "ธนาคารกรุงไทย";
$lang['Siam Commercial Bank'] 				= "ธนาคารไทยพาณิชย์";
$lang['Kasikorn Bank'] 						= "ธนาคารกสิกรไทย";
$lang['Bank of Ayudhya'] 					= "ธนาคารกรุงศรีอยุธยา";
$lang['Thanachart Bank'] 					= "ธนาคารธนชาต";
$lang['TMB Bank'] 							= "ธนาคารทหารไทย";
$lang['Kiatnakin Bank'] 					= "ธนาคารเกียรตินาคิน";
$lang['No.'] 								= "เลข. ";
$lang['shipping_status'] 					= "สถานะจัดส่ง";

$lang['transfer_date']                    	= "วันที่โอน";

$lang['transfer_total']                    	= "ยอดที่โอน";
$lang['transfer_slip']                    	= "แนบหลักฐานการชำระเงิน";

$lang['order_type']                    		= "รูปแบบคำสั่งซื้อ";
$lang['stock']                    			= "แบบสต๊อกสินค้า";
$lang['dropship']                    		= " แบบ Dropship";
$lang['sale_deleted']                    	= "ลบคำสั่งซื้อ";

$lang['district']                    		= "ตำบล";
$lang['amphoe']               	     		= "อำเภอ";
$lang['province']                    		= "จังหวัด";
$lang['zipcode']                    		= "รหัสไปรษณีย์";
$lang['user_register_form']            		= "สมัครตัวแทน";
$lang['seller_id']            				= "รหัสตัวแทน";
$lang['total_point : ']            			= "รวมคะแนนสะสมทั้งหมด : ";
$lang['point_balance']            			= "คะแนนสะสม";

$lang['or']            						= "หรือ";
$lang['login']            					= "ล็อกอินเข้าระบบ";
$lang['signin_to_agent']            		= "สมัครเป็นตัวแทน";

$lang['prefix']            					= "คำนำหน้า";
$lang['digits']            					= "จำนวนหลัก";
$lang['number_current']            			= "เลขปัจจุบัน";
$lang['advisor']            				= "กรอกรหัสผู้แนะนำ";

$lang['user_infomation']            		= "ข้อมูลการเข้าสู่ระบบ";

$lang['agent_warn']                         = "ยังไม่ได้สร้างบัตรตัวแทน กรุณาคลิ๊กที่ปุ่ม สร้างบัตรตัวแทน";

$lang['I_agree_to_the']            			= "ฉันยอมรับใน";
$lang['terms_and_conditions_of_agent']      = "ข้อตกลงและเงื่อนไขการสมัครตัวแทน";


$lang['select_agent']            		= "เลือกตัวแทน";
$lang['all_agent']            			= "ตัวแทนทั้งหมด";
$lang['card_no']            			= "เลขบัตรประชาชน";
$lang['profile_picture']            	= "รูปโปรไฟล์";

$lang['start_date']            			= "วันที่เริ่มต้น";
$lang['end_date']            			= "วั้นที่สิ้นสุด";

$lang['new_password_to_send_sms']       = "ระบบได้ส่งรหัสผ่านใหม่ไปทาง sms หมายเลขโทรศัพท์ของท่านเรียบร้อยแล้ว";
$lang['fail_to_send_%s_sms!']       		= "ไม่สามารถส่ง sms ยัง %s ได้";
$lang['fail_to_send_sms!.']       		= "ไม่สามารถส่ง sms ได้ เนื่องจากหมายเลขโทรศัพท์ของท่าน ไม่ถูกต้อง";

$lang['send_sms_new_password_to_phone_%s']   = "ระบบได้ส่งรหัสผ่านใหม่ทาง sms หมายเลขโทรศัพท์ %s เรียบร้อยแล้ว";
$lang['email_address_or_phone_number']   = "กรุณากรอกอีเมลหรือหมายเลขโทรศัพท์ของคุณ";

$lang['email_or_phone']   = "อีเมลหรือหมายเลขโทรศัพท์";



$lang['register_success_full']    = "คุณได้สมัครตัวแทนเสร็จเรียบร้อยแล้ว กรุณาแจ้งแม่ทีมเพื่อเปิดให้คุณสามารถเข้าใช้งานระบบ";
$lang['register_error']   			= "สมัครสมาชิกไม่สำเร็จ";

$lang['account_creation_duplicate_email.']   			= "อีเมลล์ นี้มีผู้ใช้แล้ว กรุรากรอกข้อมูลสมัครใหม่อีกครั้ง";
$lang['account_creation_duplicate_username.']   			= "ชื่อล็อคอิน นี้มีผู้ใช้แล้ว กรุรากรอกข้อมูลสมัครใหม่อีกครั้ง";
$lang['please_contact_the_facilitator_or_team_mate_for_a_link_to_apply_for_a_new_agent.']   			= "ลิงค์สำหรับสมัครตัวแทนนี้ไม่ถูกต้อง กรุณาติดต่อผู้แนะนำหรือแม่ทีมเพื่อขอลิงค์ในการสมัครตัวแทนใหม่";
$lang['please_contact_the_facilitator_or_the_parent_team_to_request_a_representative_subscription_link.']   				= "กรุณาติดต่อผู้แนะนำหรือแม่ทีมเพื่อขอลิงค์สำหรับสมัครสมาชิกตัวแทน";