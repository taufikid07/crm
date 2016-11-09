<?php
// $Id: main.php, 2008/03/03 Fujicon Priangan Perdana, Inc.
// Text lable for Daily Report Module
// Japanese version

define('_DR_ACT_REPORT', '作業情報');
define('_DR_ACTION', '有効');
define('_DR_STATUS', 'ステータス');
define('_DR_NOTE', 'メモ');
define('_DR_ACT_LIST', '業務内容リスト');
define('_DR_NAME', 'スタッフ名');
define('_DR_YEAR', '年');
define('_DR_MONTH', '月');
define('_DR_SHOW', '表示する');
define('_DR_PAGE', 'ページ');
define('_DR_IN', '入室');
define('_DR_OUT', '退室');
define('_DR_TIME_CARD', 'タイムカード');
define('_DR_DATE', '日付');
define('_DR_DAY', '日');
define('_DR_HOLIDAY', '休日');
define('_DR_ACTIVITY', '業務内容');
define('_DR_WORK_TIME', '業務時間');
define('_DR_TOTAL', '合計');
define('_DR_INPUT', '入力');
define('_DR_PROJECT', 'プロジェクト');
define('_DR_LEVEL', 'レベル');
define('_DR_REPORT', '報告');
define('_DR_TIME', '時間');
define('_DR_UNTIL', '〜');
define('_DR_NEXT_DAY_TASK', '明日の予定');
define('_DR_SEND_MAIL_DAYS', 'Send Mail This Days');
define('_DR_SEND_MAIL_CHECK', 'Check for send mail');
define('_DR_CONTINUE', '引き続き');
define('_DR_FINISH', '完了');
define('_DR_NONE', '無し');
define('_DR_ANOTHER', 'その他プロジェクト');
define('_DR_PROGRESS', '進捗状況');
define('_DR_VIEW_REPORT', '日報一覧');
define('_DR_DAILY_REPORT', '日報');
define('_DR_MONTHLY_REPORT', '作業月報');
define('_DR_ADD', '追加');
define('_DR_UPDATE', '更新');
define('_DR_PRINT_PREVIEW', '印刷プレビュー');
define('_DR_EDIT', '編集');

// Text lable for Project Manager on Daily Report Control Panel
define('_DR_PRO_LIST', 'プロジェクトリスト');
define('_DR_PRO_NAME', 'プロジェクト名');
define('_DR_SELECT_ALL', '全部選択');
define('_DR_PROJECT_EMPTY', 'プロジェクト記述無し');
define('_DR_ACTIVITY_EMPTY', 'プロジェクト内容無し');

define('_DR_DATE_EMPTY', '休日日付無し');
define('_DR_DATE_NaN', '休日日付入力のは(1-31)のフォーマットにしてください。');
define('_DR_DATE_MAX', '今月の最大の日付は');
define('_DR_DESC_EMPTY', '休日記述無し');

define('_DR_ADD_PRO', '新プロジェクト追加: ');
define('_DR_UPD_PRO', 'プロジェクト更新: ');
define('_DR_ADD_ACT', '業務内容追加: ');
define('_DR_UPD_ACT', '業務内容更新: ');
define('_DR_ADD_DAILY', '日報追加: ');
define('_DR_UPD_DAILY', '日報更新: ');

define('_DR_DEL_CONFIRM', 'このファイルを削除します。よろしいですか？');

define('_DR_ADD_HOLY', '休日追加: ');
define('_DR_UPD_HOLY', '休日更新: ');
define('_DR_DEL_HOLY', '休日削除: ');

define('_DR_STATUS_SUCCESS', '成功しました。');
define('_DR_STATUS_FALSE', '失敗しました。');

// Text label for Time Card submodule
define('_DR_TC_CODE', 'コード');
define('_DR_TC_PERSONAL', '個人のタイムカード');
define('_DR_TC_RECAPITULATION', 'タイムカード総括表');
define('_DR_TC_ATTENDANCE', '出社');
define('_DR_TC_OVERTIME', '昼残業');
define('_DR_TC_ATT_HOLIDAY', '休日出勤');
define('_DR_TC_FREQUENCY', '回数');
define('_DR_TC_DURATION', '時数');
define('_DR_TC_DAY', '日');
define('_DR_TC_HOUR', '時間');
define('_DR_TC_TIME', '回');
define('_DR_TC_ABSENT', '欠勤');
define('_DR_TC_NIGHT_DUTY', '夜勤');
define('_DR_TC_OVERDUE', '延滞');
define('_DR_TC_GOBACK_EARLIER', '早退');
define('_DR_TC_NOTE', 'メモ');

// new
define('DR_HOLIDAY_THIS_MONTH', '今月の休日');
define('_DR_ID', 'ID');
define('_DR_CODE', 'コード');
define('_DR_OLD', '旧');
define('_DR_NEW', '新');
define('_DR_EDITOR', '編集');
define('_DR_MAN_DAY', '人数');
define('_DR_OVERTIME', '残業');
define('_DR_EVENTIDE', '時間外');
define('_DR_NIGHT', '深夜');
define('_DR_SEAL', '出社印');

define('_DR_SUM_DAY_OVERTIME', '昼残業 	換算残業時間 (h1.50) =');
define('_DR_SUM_NIGHT_OVERTIME', '夜勤 	換算残業時間 (h2.00) =');
define('_DR_MAN_PERDAY', '延べ作業人数　(8h/日) =');

define('_DR_JOB_DETAIL', '仕事の詳細');
define('_DR_DEADLINE', '期限');	
define('_DR_TODAY', '今日');
define('_DR_YESTERDAY', '昨日');

define('_DR_CONTINUE_2', '引き続き');
define('_DR_DISPLAY', '表示する');
define('_DR_NO_DATA', "表示のデータがありません。");

//////////////////////////////////// Message //////////////////////////////////////////

// for Daily Report

define('_MSG_NOTE_BLANK', '入力フォームの部分を確認してください。');
define('_MSG_TASK_CHECKED', '明日の予定を「」記入してください。');
define('_MSG_PROGRESS_BLANK', '進歩を記入してください。');
define('_MSG_PROGRES_NOT_NUMBER', '進歩は数「1-100」を記入してください。');

define('_MSG_SAVE_PROJECT_SUCCESS', '新プロジェクトの登録が完了しました!');
define('_MSG_SAVE_PROJECT_FAIL', '新プロジェクトの登録が失敗しました!');
define('_MSG_UPD_PROJECT_SUCCESS', 'プロジェクトが更新されました!');
define('_MSG_UPD_PROJECT_FAIL', 'プロジェクトの更新が出来ませんでした!');

define('_MSG_SAVE_ACTIVITY_SUCCESS', '新業務内容の登録が完了しました!');
define('_MSG_SAVE_ACTIVITY_FAIL', '新業務内容の登録が失敗しました!');
define('_MSG_UPD_ACTIVITY_SUCCESS', '業務内容が更新されました!');
define('_MSG_UPD_ACTIVITY_FAIL', '業務内容の更新が出来ませんでした!');

define('_MSG_DEL_TC_SUCCESS', 'タイムカードを削除できました!');
define('_MSG_DEL_TC_FAIL', 'タイムカードを削除できません!');
define('_MSG_UPD_TC_SUCCESS', 'タイムカードが更新されました!');
define('_MSG_UPD_TC_FAIL', 'タイムカードの更新が出来ませんでした!');

define('_MSG_SAVE_HOLIDAY_SUCCESS', '新休日の登録が完了しました!');
define('_MSG_SAVE_HOLIDAY_FAIL', '新休日の登録が失敗しました!');
define('_MSG_UPD_HOLIDAY_SUCCESS', '休日が更新されました!');
define('_MSG_UPD_HOLIDAY_FAIL', '休日を更新できません!');
define('_MSG_DEL_HOLIDAY_SUCCESS', '休日を削除できました!');
define('_MSG_DEL_HOLIDAY_FAIL', '休日を削除できません!');

define('_MSG_SAVE_DAILY_SUCCESS', '工程の登録が完了しました!');
define('_MSG_SAVE_DAILY_FAIL', '工程の登録が失敗しました!');
define('_MSG_UPD_DAILY_SUCCESS', '工程の編集が完了しました!');
define('_MSG_UPD_DAILY_FAIL', '工程の編集が出来ませんでした!');
define('_MSG_DEL_DAILY_SUCCESS', '工程が削除できました!');
define('_MSG_DEL_DAILY_FAIL', '工程の削除が失敗しました!');
define('_MSG_NO_PERMISSION', '申し訳ありません、タイムカードをご利用する許可ありません。');

define('_MSG_DEL_CONFIRM_DAILY', 'この工程を削除します。よろしいですか？');
define('_MSG_HAS_BEEN_ABSENT', 'タイムカードデータが既に登録されています。');

define('_MSG_SEARCH_DATA_EMPTY', '検索した内容が見つかりませんでした。');

define('_DR_MD', '日');
define('_DR_MONTHLY', '作業月報');
define('_DR_ALL', '全体');

define('_DR_SHOW_CART', 'グラフを示す');
define('_DR_PERIOD', '期間');
define('_DR_PRINT_AT', '%sに印刷されました。');
define('_DR_WORK_REPORT', '作業情報');

define('_DATESTRING_TC', 'Y年m月d日のH時:i分');

define('_ALL_USER', '全部のユーザ');
define('_VIEW_USER', '参照可能なユーザ');
define('_USING_USER', '利用可能なユーザ');

define('_DR_MODE_1', 'モード 1');
define('_DR_MODE_2', 'モード ２');
?>