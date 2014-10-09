<?php


/* INIauth_hpp.php
 *
 * 이니페이 플러그인을 통해 요청된 실시간 휴대폰 빌링 등록을 처리한다.
 * 코드에 대한 자세한 설명은 매뉴얼을 참조하십시오.
 * <주의> 구매자의 세션을 반드시 체크하도록하여 부정거래를 방지하여 주십시요.
 *  
 * http://www.inicis.com
 * Copyright (C) 2004 Inicis Co., Ltd. All rights reserved.
 */

	/**************************
	 * 1. 라이브러리 인클루드 *
	 **************************/
	require("INIpay41Lib.php");
	
	
	/***************************************
	 * 2. INIpay41 클래스의 인스턴스 생성 *
	 ***************************************/
	$inipay = new INIpay41;

	

	/***********************
	 * 2. 정보 설정 *
	 ***********************/
	$inipay->m_inipayHome = "/usr/local/INIbill"; 		// 이니페이 홈디렉터리
	$inipay->m_keyPw = "1111"; 						// 키패스워드(상점아이디에 따라 변경)
	$inipay->m_type = "auth_bill"; 						// 고정 (절대 수정금지)
	$inipay->m_pgId = "INIpayATCD"; 					// 고정 (절대 수정금지)
	$inipay->m_subPgIp = "203.238.3.10"; 					// 고정 (절대 수정금지)
	$inipay->m_payMethod = $paymethod;					// 고정 (절대 수정금지)
	$inipay->m_billtype = "HPP";						// 고정 (절대 수정금지)	
	$inipay->m_debug = "true"; 						// 로그모드("true"로 설정하면 상세한 로그가 생성됨)
	$inipay->m_mid = $mid; 							// 상점아이디
	$inipay->m_goodName = $goodname; 					// 상품명 (최대 40자)
	$inipay->m_buyerName = $buyername; 					// 구매자 (최대 15자)
	$inipay->m_url = "http://test.co.kr";					// 사이트 URL
	$inipay->m_merchantReserved1 = $merchantreserved1; 			// 상점주문번호
	$inipay->m_merchantReserved3 = $merchantreserved3; 			// 회원 ID
	$inipay->m_encrypted = $encrypted;
	$inipay->m_sessionKey = $sessionkey;


	/************************************************************
	 * 3. 본인인증 절차를 통한 실시간 휴대폰 빌링 등록 요청처리 *
	 ************************************************************/
	
	$inipay->startAction();


	/************************************************************
	 *   4. 본인인증 절차를 통한 실시간 휴대폰 빌링 등록 결과   *
	 ************************************************************
	 *                                                          *
	 * $inipay->m_resultCode         // "00"이면 빌키생성 성공  *
	 * $inipay->m_resultMsg          // 결과에 대한 설명        *
	 * $inipay->m_billKey            // BILL KEY                *
	 * $inipay->m_tid                // 거래번호                * 
	 * $inipay->m_nohpp		 // 휴대폰 번호             *
	 * $inipay->m_hcorp		 // 이통사 정보             *
	 ************************************************************/
?>

<html>
<head>

<title>INIpay 실시간 휴대폰 빌링 등록 결과 샘플</title>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">

<script>
	var openwin=window.open("childwin.html","childwin","width=299,height=149");
	openwin.close();
</script>

<style type="text/css">
	BODY{font-size:9pt; line-height:160%}
	TD{font-size:9pt; line-height:160%}
	INPUT{font-size:9pt;}
	.emp{background-color:#E0EFFE;}
</style>

</head>

<body>
<table border=0 width=500>
<tr>
<td>
<hr noshade size=1>
<b> 실시간 휴대폰 빌링 등록 결과</b>
<hr noshade size=1>
</td>
</tr>
</table>
<br>

<table border=0 width=500>
	<tr>
		<td align=right nowrap>결과코드 : </td>
		<td><?php echo($inipay->m_resultCode); ?></td>
	</tr>
	<tr>
		<td align=right nowrap>결과내용 : </td>
		<td><font class=emp><?php echo($inipay->m_resultMsg); ?></font></td>
	</tr>
	<tr>
		<td align=right nowrap>BILL KEY  : </td>
		<td><?php echo($inipay->m_billKey); ?></td>
	</tr>
	<tr>
		<td align=right nowrap>휴대폰번호  : </td>
		<td><?php echo($inipay->m_nohpp); ?></td>
	</tr>	
	<tr>
		<td align=right nowrap>이통사정보  : </td>
		<td><?php echo($inipay->m_hcorp); ?></td>
	</tr>	
	<tr>
		<td colspan=2><hr noshade size=1></td>
	</tr>
	<tr>
		<td align=right colspan=2>Copyright Inicis, Co.<br>www.inicis.com</td>
	</tr>
</table>
</body>
</html>
