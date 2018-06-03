<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
* Script: pos_lang.php
*   Thai translation file
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

// For quick cash buttons -  if you need to format the currency please do it according to you system settings
$lang['quick_cash_notes']               = array('10', '20', '50', '100', '500', '1000', '5000');

$lang['pos_module']                     = "POS โมดูล";
$lang['cat_limit']                      = "แสดงหมวดหมู่";
$lang['pro_limit']                      = "แสดงสินค้า";
$lang['default_category']               = "หมวดหมู่หลัก";
$lang['default_customer']               = "ลูกค้า";
$lang['default_biller']                 = "ผู้จำหน่าย";
$lang['pos_settings']                   = "การตั้งค่า POS";
$lang['barcode_scanner']                = "เครื่องอ่านบาร์โค๊ด";
$lang['x']                              = "X";
$lang['qty']                            = "จำนวน";
$lang['total_items']                    = "จำนวนทั้งหมด";
$lang['total_payable']                  = "จำนวนที่ต้องชำระทั้งหมด";
$lang['total_sales']                    = "จำนวนคำสั่งซื้อทั้งหมด";
$lang['tax1']                           = "ภาษี 1";
$lang['total_x_tax']                    = "จำนวนภาษีทั้งหมด";
$lang['cancel']                         = "ยกเลิก";
$lang['payment']                        = "การชำระเงิน";
$lang['pos']                            = "POS";
$lang['p_o_s']                          = "POS";
$lang['today_sale']                     = "คำสั่งซื้อวันนี้";
$lang['daily_sales']                    = "คำสั่งซื้อรายวัน";
$lang['monthly_sales']                  = "คำสั่งซื้อรายเดือน";
$lang['pos_settings']                   = "การตั้งค่า POS";
$lang['loading']                        = "กำลังโหลด...";
$lang['display_time']                   = "แสดงเวลา";
$lang['pos_setting_updated']            = "ปรับปรุงการตั้งค่า POS สำเร็จแล้ว";
$lang['pos_setting_updated_payment_failed'] = "การตั้งค่า POS เรียบร้อยแล้ว แต่การตั้งค่าเกตท์เวย์ล้มเหลว โปรดลองใหม่ภายหลัง";
$lang['tax_request_failed']             = "พบปัญกาการขอภาษี!";
$lang['pos_error']                      = "เกิดข้อผิดพลาด ในการประมวลผล กรุณาเพิ่มสินค้าอีกครั้ง ขอบคุณ!";
$lang['qty_limit']                      = "จำนวนสินค้าห้ามเกิน 999.";
$lang['max_pro_reached']                = "กรุณาเพิ่มการชำระเงินนี้และเปิด เรียกเก็บเงินใหม่ สำหรับรายการต่อไป ทั้งหมด ขอบคุณ!";
$lang['code_error']                     = "การร้องขอล้มเหลว โปรดตรวจสอบและลองอีกครั้ง!";
$lang['x_total']                        = "กรุณาเพิ่มสินค้าก่อนการชำระเงิน ขอบคุณ!";
$lang['paid_l_t_payable']               = "จำนวนเงินที่จ่ายน้อยกว่าจำนวนเงินที่ต้องชำระ";
$lang['suspended_sales']                = "คำสั่งซื้อถูกระงับ";
$lang['sale_suspended']                 = "คำสั่งซื้อถูกระงับสำเร็จ.";
$lang['sale_suspend_failed']            = "คำสั่งซื้อถูกระงับ กรุณาลองใหม่อีกครั้ง!";
$lang['add_to_pos']                     = "เพิ่มไปยัง POS";
$lang['delete_suspended_sale']          = "ลบคำสั่งซื้อนี้้";
$lang['save']                           = "บันทึก";
$lang['discount_request_failed']        = "คำร้องขอล้มเหลว เกิดปัญหาเกี่ยวกับส่วนลด!";
$lang['saving']                         = "กำลังบันทึก...";
$lang['paid_by']                        = "ชำระเงินโดย";
$lang['paid']                           = "ชำระเงิน";
$lang['ajax_error']                     = "ajax ล้มเหลว, โปรดลองอีกครั้ง!";
$lang['close']                          = "ปิด";
$lang['finalize_sale']                  = "สรุปคำสั่งซื้อ";
$lang['cash_sale']                      = "ชำระด้วยเงินสด";
$lang['cc_sale']                        = "ชำระด้วยเครดิตการ์ด";
$lang['ch_sale']                        = "ชำระด้วยเช็ค";
$lang['sure_to_suspend_sale']           = "คุณต้องการระงับคำสั่งซื้อใช่หรือไม่?";
$lang['leave_alert']                    = "คุณจะสูญเสียข้อมูลคำสั่งซื้อ กดตกลงเพื่อออกและยกเลิกที่จะอยู่ในหน้านี้.";
$lang['sure_to_cancel_sale']            = "คุณแน่ใจหรือว่าต้องการที่จะยกเลิกคำสั่งซื้อ?";
$lang['sure_to_submit_sale']            = "คุณแน่ใจหรือว่า ต้องการที่จะขาย?";
$lang['alert_x_sale']                   = "คุณแน่ใจหรือไม่ว่าต้องการลบ ระงับ คำสั่งซื้อ นี้?";
$lang['suspended_sale_deleted']         = "คำสั่งซื้อถูกระงับ และลบเรียบร้อยแล้ว";
$lang['item_count_error']               = "เกิดข้อผิดพลาด ขณะที่นับรายการทั้งหมด กรุณาลองอีกครั้ง!";
$lang['x_suspend']                      = "กรุณาเพิ่มสินค้าก่อนที่จะระงับคำสั่งซื้อ ขอบคุณ!";
$lang['x_cancel']                       = "ไม่พบสินค้า ขอบคุณ!";
$lang['yes']                            = "ใช่";
$lang['no1']                            = "ไม่";
$lang['suspend']                        = "ระงับ";
$lang['order_list']                     = "รายชื่อ การสั่งซื้อ";
$lang['print']                          = "พิมพ์";
$lang['cf_display_on_bill']             = "ข้อมูลที่ถูกกำหนดจะแสดงในใบเสร็จรับเงิน POS";
$lang['cf_title1']                      = "การกำหนดเอง1";
$lang['cf_value1']                      = "มูบค่ากำหนดเอง1";
$lang['cf_title2']                      = "การกำหนดเอง2";
$lang['cf_value2']                      = "มูลค่ากำหนดเอง2";
$lang['cash']                           = "เงินสด";
$lang['cc']                             = "บัตรเครดิต";
$lang['cheque']                         = "เช็ค";
$lang['cc_no']                          = "เลขที่บัตรเครดิต";
$lang['cc_holder']                      = "ชื่อเจ้าของ";
$lang['cheque_no']                      = "ไม่มีเลขที่เช็ค";
$lang['email_sent']                     = "อีเมลล์ส่งสำเร็จแล้ว!";
$lang['email_failed']                   = "ส่งอีเมลล้มเหลว!";
$lang['back_to_pos']                    = "กลับไป POS";
$lang['shortcuts']                      = "ทางลัด";
$lang['shortcut_key']                   = "คีย์ลัด";
$lang['shortcut_keys']                  = "คีย์ลัด";
$lang['keyboard']                       = "แป้นพิมพ์";
$lang['onscreen_keyboard']              = "บนหน้าจอแป้นพิมพ์";
$lang['focus_add_item']                 = "เพิ่มการป้อนรายการข้อมูล";
$lang['add_manual_product']             = "เพิ่มรายการ คู่มือการ ขาย";
$lang['customer_selection']             = "การป้อนข้อมูล ของลูกค้า";
$lang['toggle_category_slider']         = "สลับตำแหน่งหมวดหมู่";
$lang['toggle_subcategory_slider']      = "สลับตำแหน่งหมวดหมู่ทั้งหมด";
$lang['cancel_sale']                    = "ยกเลิกคำสั่งซื้อ";
$lang['suspend_sale']                   = "ระงับคำสั่งซื้อ";
$lang['print_items_list']               = "พิมพ์รายการสินค้า";
$lang['finalize_sale']                  = "สรุปคำสั่งซื้อ";
$lang['open_hold_bills']                = "การเปิดขายถูกระงับ";
$lang['search_product_by_name_code']    = "ค้นหาสินค้าโดย ชื่อ/รหัส";
$lang['receipt_printer']                = "เครื่องพิมพ์ใบเสร็จ";
$lang['cash_drawer_codes']              = "รหัสเปิดลิ้นชักเก็บเงิน";
$lang['pos_list_printers']              = "พิมพ์รายการ POS (Separated by |)";
$lang['custom_fileds']                  = "กำหนดใบเสร็จรับเงินเอง";
$lang['shortcut_heading']               = "Ctrl, Shift and Alt with และตัวอักษรอื่นๆ (Ctrl+Shift+A). ฟังก์ชันคีย์ ( F1 - F12 ) ได้รับการสนับสนุน มากเกินไป";
$lang['product_button_color']           = "สีของสินค้า";
$lang['edit_order_tax']                 = "แก้ไข คำสั่งซื้อภาษี";
$lang['select_order_tax']               = "เลือกภาษีการสั่งซื้อ";
$lang['paying_by']                      = "ชำระเงินด้วย";
$lang['paypal_pro']                     = "Paypal Pro";
$lang['stripe']                         = "Stripe";
$lang['swipe']                          = "Swipe";
$lang['card_type']                      = "ประเภทของบัตร";
$lang['Visa']                           = "วีซ่า";
$lang['MasterCard']                     = "มาสเตอร์การ์ด";
$lang['Amex']                           = "เอเม็กซ์";
$lang['Discover']                       = "ค้นพบ";
$lang['month']                          = "เดือน";
$lang['year']                           = "ปี";
$lang['cvv2']                           = "รหัสรักษาความปลอดภัย";
$lang['total_paying']                   = "รวมยอดจ่ายทั้งหมด";
$lang['balance']                        = "ยอดคงเหลือ";
$lang['serial_no']                      = "serial no";
$lang['product_discount']               = "ส่วนลดสินค้า";
$lang['max_reached']                    = "วงเงินที่ ได้รับอนุญาตสูงสุด.";
$lang['add_more_payments']              = "เพิ่ม การชำระเงิน อื่น ๆ";
$lang['sell_gift_card']                 = "ขายกิฟท์การ์ด";
$lang['gift_card']                      = "กิฟท์การ์ด";
$lang['product_option']                 = "ตัวเลือกสินค้า";
$lang['card_no']                        = "เลขที่บัตร";
$lang['value']                          = "มูลค่า";
$lang['paypal']                         = "Paypal";
$lang['sale_added']                     = "เพิ่มรายงาน POS เรียบร้อย";
$lang['invoice']                        = "ใบกำกับสินค้า";
$lang['vat']                            = "ภาษีมูลค่าเพิ่ม";
$lang['web_print']                      = "พิมพ์เว็บ";
$lang['ajax_request_failed']            = "คำขอล้มเหลว โปรดลองอีกครั้ง";
$lang['pos_config']                     = "การกำหนดค่า POS";
$lang['default']                        = "ค่าเริ่มต้น";
$lang['primary']                        = "ระดับประถมศึกษา";
$lang['info']                           = "ข้อมูล";
$lang['warning']                        = "คำเตือน";
$lang['danger']                         = "อันตราย";
$lang['enable_java_applet']             = "เปิดใช้งาน Java Applet";
$lang['update_settings']                = "บันทึกการตั้งค่า";
$lang['open_register']                  = "เปิดการสมัครสมาชิก";

$lang['close_register']                 = "ปิดการรับสมัครสมาชิก";
$lang['cash_in_hand']                   = "เงินสดในมือ";
$lang['total_cash']                     = "เงินทั้งหมด";
$lang['total_cheques']                  = "เช็คทั้งหมด";
$lang['total_cc_slips']                 = "สลิปบัตรเครดิตทั้งหมด";
$lang['CC']                             = "บัตรเครดิต";
$lang['register_closed']                = "การลงทะเบียนปิด เรียบร้อยแล้ว";
$lang['register_not_open']              = "ไม่ได้เปิดการสมัครสมาชิก,กรุณากรอกจำนวนเงิน และสมัครสมาชิก";
$lang['welcome_to_pos']                 = "ยินดีต้อนรับสู่ POS";
$lang['tooltips']                       = "เคล็ดลับปุ่มเครื่องมือ";
$lang['previous']                       = "ก่อนหน้า";
$lang['next']                           = "ถัดไป";
$lang['payment_gateways']               = "การชำระเงิน";
$lang['stripe_secret_key']              = "รหัสที่มีความสำคัญ";
$lang['stripe_publishable_key']         = "รหัสสำคัญ(เผยแผ่)";
$lang['APIUsername']                    = "Paypal Pro API ชื่อผู้ใช้";
$lang['APIPassword']                    = "Paypal Pro API รหัสผ่าน";
$lang['APISignature']                   = "Paypal Pro API ลายเซ็นต์";
$lang['view_bill']                      = "ดูบิล";
$lang['view_bill_screen']               = "ดูบิลจากหน้าจอ";
$lang['opened_bills']                   = "เปิดบิล";
$lang['leave_opened']                   = "ปิดบิล";
$lang['delete_bill']                    = "ลบบิล";
$lang['delete_all']                     = "ลบทั้งหมด";
$lang['transfer_opened_bills']          = "เปิดการโอนเงิน";
$lang['paypal_empty_error']             = "Paypal การทำธุรกรรมล้มเหลว (ข้อผิดพลาด array กลับไป)";
$lang['payment_failed']                 = "<strong>ไม่สามารถชำระเงิน!</strong>";
$lang['pending_amount']                 = "อยู่ระหว่างดำเนินการ";
$lang['available_amount']               = "จำนวน ที่มีจำหน่าย";
$lang['stripe_balance']                 = "คงเหลือ";
$lang['paypal_balance']                 = "Paypal สมดุล";
$lang['view_receipt']                   = "ดูใบเสร็จรับเงิน";
$lang['rounding']                       = "ปัดเศษ";
$lang['ppp']                            = "Paypal Pro";
$lang['delete_sale']                    = "ลบคำสั่งซื้อ";
$lang['return_sale']                    = "ส่งคืนสินค้า";
$lang['edit_sale']                      = "แก้ไขคำสั่งซื้อ";
$lang['email_sale']                     = "อีเมลล์คำสั่งซื้อ";
$lang['add_delivery']                   = "เพิ่ม การจัดส่ง";
$lang['add_payment']                    = "เพิ่มช่องทางการชำระเงิน";
$lang['view_payments']                  = "ดูการชำระเงิน";
$lang['no_meil_provided']               = "ไม่มีที่อยู่อีเมลล์";
$lang['payment_added']                  = "เพิ่มช่องทางการชำระเงินสำเร็จแล้ว";
$lang['suspend_sale']                   = "ระงับคำสั่งซื้อ";
$lang['reference_note']                 = "หมายเหตุที่ใช้ในการอ้างอิง";
$lang['type_reference_note']            = "หมายเหตุที่ใช้ในประเภทการอ้างอิงที่ต้องการระงับ";
$lang['change']                         = "เปลี่ยน";
$lang['quick_cash']                     = "เงินสด";
$lang['sales_person']                   = "ขายสมทบ";
$lang['no_opeded_bill']                 = "ไม่พบใบสั่งซื้อ";
$lang['please_update_settings']         = "โปรดอัพเดทและตั้งค่าก่อนใช้งานโปรแกรม POS";
$lang['order']                          = "ใบสั่งซื้อ";
$lang['bill']                           = "บิล";
$lang['due']                            = "เนื่องจาก";
$lang['paid_amount']                    = "ยอดเงินที่จ่าย";
$lang['due_amount']                     = "เนื่องจากยอดเงิน";
$lang['edit_order_discount']            = "แก้ไขส่วนลดการสั่งซื้อ";
$lang['sale_note']                      = "บันทึกคำสั่งซื้อ";
$lang['staff_note']                     = "บันทึกพนักงาน";
$lang['list_open_registers']            = "รายการลงทะเบียน";
$lang['open_registers']                 = "เปิดลงทะเบียน";
$lang['opened_at']                      = "เปิด";
$lang['all_registers_are_closed']       = "การลงทะเบียนทั้งหมดจะถูกปิด";
$lang['review_opened_registers']        = "กรุณาตรวจสอบการลงทะเบียนทั้งหมดด้านล่าง";
$lang['suspended_sale_loaded']          = "ระงับการโหลดคำสั่งซื้อเรียบร้อยแล้ว";
$lang['incorrect_gift_card']            = "กิฟท์การ์ดนี้ไม่ถูกต้องหรือหมดอายุแล้ว.";
$lang['gift_card_not_for_customer']     = "กิฟท์การ์ดนี้ใช้ไม่ได้สำหรับลูกค้ารายนี้.";
$lang['delete_sales']                   = "ลบคำสั่งซื้อ";
$lang['click_to_add']                   = "กรุณาคลิกที่ ปุ่มด้านล่างเพื่อ เปิด";
$lang['tax_summary']                    = "ข้อมูลภาษีแบบย่อ";
$lang['qty']                            = "จำนวน";
$lang['tax_excl']                       = "ยกเว้นภาษี";
$lang['tax_amt']                        = "ภาษี";
$lang['total_tax_amount']               = "รวมภาษีทั้งหมด ";
$lang['tax_invoice']                    = "ใบกำกับภาษี";
$lang['char_per_line']                  = "จำนวนตัวอักษรต่อบรรทัด";
$lang['delete_code']                    = "รหัส PIN";
$lang['quantity_out_of_stock_for_%s']   = "จำนวนสินค้าที่หมดคลังสินค้า %s";
$lang['refunds']                        = "การคืนเงิน";
$lang['register_details']               = "รายละเอียดการลงทะเบียน";
$lang['payment_note']                   = "บันทึกการชำระเงิน";
$lang['to_nearest_005']                 = "ไปจำนวนที่ใกล้ที่สุด 0.05";
$lang['to_nearest_050']                 = "ไปจำนวนที่ใกล้ที่สุด  0.50";
$lang['to_nearest_number']              = "ไปจำนวนที่ใกล้ที่สุด (จำนวนเต็ม)";
$lang['to_next_number']                 = "ไปยังหมายเลขถัดไป (จำนวนเต็ม)";
$lang['update_heading']                 = "หน้านี้จะช่วยให้คุณตรวจสอบและติดตั้งโปรแกรมเวอร์ชั่นล่าสุด ได้อย่างง่ายดาย ด้วยการคลิกเพียงครั้งเดียว. <strong>หากมีมากกว่า 1 เวอร์ชั่น กรุณาอัปเดตโดยเริ่มต้นจากเวอร์ชั่นด้านบน (รุ่นต่ำสุด)</strong>.";
$lang['update_successful']              = "ปรับปรุงรายการเรียบร้อยแล้ว";
$lang['using_latest_update']            = "คุณกำลังใช้ รุ่นล่าสุด.";
$lang['return_policy']                  = "รับคืนสินค้าภายใน 24 ชั่วโมง และต้องนำใบเสร็จมาด้วยทุกครั้ง";
$lang['membershipcard_request']         = "ลงทะเบียนรับบัตรสมาชิกฟรีวันนี้ สำหรับส่วนลดในครั้งต่อไป.";
$lang['welcome_customer_meessage']      = "สวัสดีฉันชื่อ ";
$lang['service_customer_meessage']      = " ยินดีให้บริการ.";
$lang['payment_id']                     = "รหัสการชำระเงิน";
$lang['customer_thanks']                = "รหัสการชำระเงิน";
$lang['customer_name']                  = "ชื่อลูกค้า:"; 


$lang['sale_no_ref']                    = "เลขที่อ้างอิงคำสั่งซื้อ";
$lang['sale_status']                    = "สถานะคำสั่งซื้อ";
$lang['duplicate_sale']                 = "คัดลอกคำสั่งซื้อ";
$lang['reference']						= "อ้างอิง";
$lang['add_printer']						= "เพิ่มเครื่องปริ้น";
$lang['save_printer']				= "บันทึก";