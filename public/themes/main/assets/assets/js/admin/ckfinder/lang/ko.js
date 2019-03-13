/*
 * CKFinder
 * ========
 * http://ckfinder.com
 * Copyright (C) 2007-2010, CKSource - Frederico Knabben. All rights reserved.
 *
 * The software, this file and its contents are subject to the CKFinder
 * License. Please read the license.txt file before using, installing, copying,
 * modifying or distribute this file or part of its contents. The contents of
 * this file is part of the Source Code of CKFinder.
 *
 */

/**
 * @fileOverview Defines the {@link CKFinder.lang} object, for the English
 *		language. This is the base file for all translations.
 */

/**
 * Constains the dictionary of language entries.
 * @namespace
 */

// Korean language file. (20101110 Jeong Hyeok)
CKFinder.lang['ko'] =
{
	appTitle : 'CKFinder',

	// Common messages and labels.
	common :
	{
		// Put the voice-only part of the label in the span.
		unavailable		: '%1<span class="cke_accessibility">, 알수없음</span>',
		confirmCancel	: '옵션 중 일부는 변경되었습니다. 대화 상자를 닫으시겠습니까?',
		ok				: '확인',
		cancel			: '취소',
		confirmationTitle	: '확인',
		messageTitle	: '정보',
		inputTitle		: '질문',
		undo			: '취소',
		redo			: '재실행',
		skip			: '건너뛰기',
		skipAll			: '전체 건너뛰기',
		makeDecision	: '조치를 취하셨습니까?',
		rememberDecision: '결정을 기억하세요'
	},


	dir : 'ltr',
	HelpLang : 'ko',
	LangCode : 'ko',

	// Date Format
	//		d    : Day
	//		dd   : Day (padding zero)
	//		m    : Month
	//		mm   : Month (padding zero)
	//		yy   : Year (two digits)
	//		yyyy : Year (four digits)
	//		h    : Hour (12 hour clock)
	//		hh   : Hour (12 hour clock, padding zero)
	//		H    : Hour (24 hour clock)
	//		HH   : Hour (24 hour clock, padding zero)
	//		M    : Minute
	//		MM   : Minute (padding zero)
	//		a    : Firt char of AM/PM
	//		aa   : AM/PM
	DateTime : 'yyyy-mm-dd hh:MM aa',
	DateAmPm : ['AM','PM'],

	// Folders
	FoldersTitle	: '폴더',
	FolderLoading	: '로딩중...',
	FolderNew		: '새 폴더의 이름을 입력하세요: ',
	FolderRename	: '새 폴더의 이름을 입력하세요: ',
	FolderDelete	: '"%1" 폴더를 삭제하시겠습니까?',
	FolderRenaming	: ' (이름변경중...)',
	FolderDeleting	: ' (삭제하는중...)',

	// Files
	FileRename		: '새 파일의 이름을 입력하세요: ',
	FileRenameExt	: '파일의 확장명을 바꾸면 사용할 수 없게 될 수도 있습니다. 바꾸시겠습니까?',
	FileRenaming	: '이름변경중...',
	FileDelete		: '"%1" 파일을 삭제하시겠습니까?',
	FilesLoading	: '로딩중...',
	FilesEmpty		: '빈폴더',
	FilesMoved		: '파일 %1 을 %2:%3 으로 이동합니다',
	FilesCopied		: '파일 %1 을 %2:%3 으로 복사합니다.',

	// Basket
	BasketFolder		: '보관함',
	BasketClear			: '보관함 비우기',
	BasketRemove		: '보관함에서 제거',
	BasketOpenFolder	: '상위 폴더 열기',
	BasketTruncateConfirm : '보관함의 모든 파일을 제거하시겠습니까?',
	BasketRemoveConfirm	: '보관함에서 %1 파일을 제거하시겠습니까?',
	BasketEmpty			: '보관함에 파일이 없습니다,\'n\'드래그해서 놓으세요.',
	BasketCopyFilesHere	: '보관함에서 파일을 복사',
	BasketMoveFilesHere	: '보관함에서 파일을 이동',

	BasketPasteErrorOther	: '파일 %s 에러: %e',
	BasketPasteMoveSuccess	: '다음 파일이 이동되었습니다: %s',
	BasketPasteCopySuccess	: '다음 파일이 복사되었습니다: %s',

	// Toolbar Buttons (some used elsewhere)
	Upload		: '업로드',
	UploadTip	: '새파일 업로드',
	Refresh		: '새로고침',
	Settings	: '설정',
	Help		: '도움말',
	HelpTip		: '도움말팁',

	// Context Menus
	Select			: '선택',
	SelectThumbnail : '미리보기 선택',
	View			: '보기',
	Download		: '다운로드',

	NewSubFolder	: '새 하위폴더',
	Rename			: '이름바꾸기',
	Delete			: '삭제',

	CopyDragDrop	: '여기로 파일 복사',
	MoveDragDrop	: '여기로 파일 이동',

	// Dialogs
	RenameDlgTitle		: '이름바꾸기',
	NewNameDlgTitle		: '새이름',
	FileExistsDlgTitle	: '파일이 이미 존재함',
	SysErrorDlgTitle : '시스템 에러',

	FileOverwrite	: '덮어쓰기',
	FileAutorename	: '자동 이름바꾸기',

	// Generic
	OkBtn		: '확인',
	CancelBtn	: '취소',
	CloseBtn	: '닫기',

	// Upload Panel
	UploadTitle			: '새파일 업로드',
	UploadSelectLbl		: '업로드할 파일을 선택',
	UploadProgressLbl	: '(업로드중입니다, 잠시만 기다려주세요...)',
	UploadBtn			: '선택한 파일 업로드',
	UploadBtnCancel		: '취소',

	UploadNoFileMsg		: '컴퓨터에서 파일을 선택하세요.',
	UploadNoFolder		: '업로드하기 전에 폴더를 선택하세요',
	UploadNoPerms		: '허용되지 않는 파일입니다.',
	UploadUnknError		: '파일 전송에 오류가 발생하였습니다.',
	UploadExtIncorrect	: '파일 확장명이 해당 폴더에 허용되지 않습니다.',

	// Settings Panel
	SetTitle		: '설정',
	SetView			: '보기:',
	SetViewThumb	: '미리보기',
	SetViewList		: '목록',
	SetDisplay		: '표시:',
	SetDisplayName	: '파일명',
	SetDisplayDate	: '날짜',
	SetDisplaySize	: '파일크기',
	SetSort			: '정렬:',
	SetSortName		: '파일명으로 정렬',
	SetSortDate		: '날짜로 정렬',
	SetSortSize		: '파일크기로 정렬',

	// Status Bar
	FilesCountEmpty : '<빈폴더>',
	FilesCountOne	: '1 파일',
	FilesCountMany	: '%1 파일들',

	// Size and Speed
	Kb				: '%1 kB',
	KbPerSecond		: '%1 kB/s',

	// Connector Error Messages.
	ErrorUnknown	: '잘못된 요청으로 처리가 불가능합니다. (에러 %1)',
	Errors :
	{
	 10 : '잘못된 명령.',
	 11 : '리소스 형식 요청에 지정되지 않았습니다.',
	 12 : '요청한 리소스의 유형이 유효하지 않습니다.',
	102 : '잘못된 파일 또는 폴더 이름.',
	103 : '승인되지 않은 요청이므로 불가능 합니다.',
	104 : '파일 시스템 권한이 없으므로 불가능 합니다.',
	105 : '잘못된 파일 확장명.',
	109 : '잘못된 요청.',
	110 : '알수없는 오류.',
	115 : '파일이나 폴더에 동일한 이름이 이미 존재합니다.',
	116 : '폴더를 찾을 수 없습니다. 새로고침 및 다시 시도하십시오.',
	117 : '파일을 찾을 수 없습니다. 파일 목록을 새로고침 및 다시 시도하십시오.',
	118 : '원본과 대상 경로가 동일합니다.',
	201 : '동일한 이름을 가진 파일이 이미 사용중입니다. 업로드된 파일명이 "%1" 로 변경되었습니다.',
	202 : '잘못된 파일',
	203 : '잘못된 파일. 파일 크기가 너무 큽니다.',
	204 : '업로드된 파일이 손상되었습니다.',
	205 : '임시 폴더는 서버에 업로드하기 위해 사용할 수 없습니다.',
	206 : '보안상 업로드가 취소되었습니다. 파일에 HTML이 포함되어 있습니다.',
	207 : '업로드된 파일은 "%1" 로 변경되었습니다.',
	300 : '파일(들) 이동에 실패했습니다.',
	301 : '파일(들) 복사에 실패했습니다.',
	500 : '보안상 파일 브라우저를 사용할 수 없습니다. 시스템 관리자에게 문의하여 CKFinder 환경설정 파일을 확인하시기 바랍니다.',
	501 : '미리보기를 지원하지 않습니다.'
	},

	// Other Error Messages.
	ErrorMsg :
	{
		FileEmpty		: '파일 이름이 없습니다.',
		FileExists		: '파일 %s 이 이미 존재합니다.',
		FolderEmpty		: '폴더 이름은 비워둘 수 없습니다',

		FileInvChar		: '파일 이름에 다음 문자를 포함할 수 없습니다: \n\\ / : * ? " < > |',
		FolderInvChar	: '폴더 이름에 다음 문자를 포함할 수 없습니다: \n\\ / : * ? " < > |',

		PopupBlockView	: '새창을 열 수 없습니다. 이 사이트에 대한 귀하의 브라우저에 팝업 설정을 허용해 주세요.'
	},

	// Imageresize plugin
	Imageresize :
	{
		dialogTitle		: '크기조절 %s',
		sizeTooBig		: '원본 이미지 크기보다 높이 또는 너비를 크게 설정할 수 없습니다 (%size).',
		resizeSuccess	: '이미지 크기조절 성공',
		thumbnailNew	: '새 미리보기 만들기',
		thumbnailSmall	: 'Small (%s)',
		thumbnailMedium	: 'Medium (%s)',
		thumbnailLarge	: 'Large (%s)',
		newSize			: '새 사이즈 설정',
		width			: '너비',
		height			: '높이',
		invalidHeight	: '잘못된 높이.',
		invalidWidth	: '잘못된 너비.',
		invalidName		: '잘못된 파일명.',
		newImage		: '새 이미지 만들기',
		noExtensionChange : '파일 확장명을 변경할 수 없습니다.',
		imageSmall		: '원본이미지가 너무 작습니다.',
		contextMenuName	: '크기조절'
	},

	// Fileeditor plugin
	Fileeditor :
	{
		save			: '저장',
		fileOpenError	: '파일을 열 수 없습니다.',
		fileSaveSuccess	: '파일이 저장되었습니다..',
		contextMenuName	: '수정',
		loadingFile		: '파일 로딩중, 잠시만 기다려주세요...'
	}
};
