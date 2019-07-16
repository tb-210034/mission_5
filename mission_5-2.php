<?php
/*データベースへの接続*/
 $dsn = "データベース名";
 $user = "ユーザー名";
 $password = "パスワード";
 $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

/*テーブルの作成*/
 $sql_new = "CREATE TABLE IF NOT EXISTS mission_5 (id INT AUTO_INCREMENT PRIMARY KEY, name CHAR(32), comment TEXT, date CHAR(32))";
 $stmt_new = $pdo -> query($sql_new);

/*名前欄とコメント欄に入力されたとき*/
 if(!empty($_POST['name']) && !empty($_POST['comment'])){
	/*サブ編集対象番号欄が空のとき（新規投稿のとき）*/
	 if(empty($_POST['edit_sub'])){
		/*パスワード欄に「intern」が入力されたとき*/
		 if($_POST['pass_new'] == "intern"){
			/*データの挿入*/
			 $name = $_POST['name'];//名前欄に入力された文字
			 $comment = $_POST['comment'];//コメント欄に入力された文字
			 $date = new DateTime();
			 $date = $date -> format('Y/m/d H:i:s');
			 $sql_insert = "INSERT INTO mission_5 (name, comment, date) VALUES (:name, :comment, :date)";
			 $stmt_insert = $pdo -> prepare($sql_insert);
			 $stmt_insert -> bindParam(':name', $name, PDO::PARAM_STR);
			 $stmt_insert -> bindParam(':comment', $comment, PDO::PARAM_STR);
			 $stmt_insert -> bindParam(':date', $date, PDO::PARAM_STR);
			 $stmt_insert -> execute();
		 }
		/*パスワード欄に「intern」以外が入力されたとき*/
		 else{
			echo "パスワードが違います。";
		 }
	 }
	/*サブ編集対象番号欄が空でないとき（編集のとき）*/
	 else{
		/*データの更新*/
		 $id = $_POST['edit_sub']; //変更したい投稿番号
		 $name = $_POST['name'];//変更したい名前
		 $comment = $_POST['comment']; //変更したいコメント
		 $date = new DateTime();
		 $date = $date -> format('Y/m/d H:i:s');
		 $sql_update = "UPDATE mission_5 SET name=:name,comment=:comment,date=:date WHERE id=:id";
		 $stmt_update = $pdo -> prepare($sql_update);
		 $stmt_update -> bindParam(':id', $id, PDO::PARAM_INT);
		 $stmt_update -> bindParam(':name', $name, PDO::PARAM_STR);
		 $stmt_update -> bindParam(':comment', $comment, PDO::PARAM_STR);
		 $stmt_update -> bindParam(':date', $date, PDO::PARAM_STR);
		 $stmt_update -> execute();
	 }
 }
/*削除対象番号欄に入力されたとき*/
 elseif(!empty($_POST['delete'])){
	/*パスワード欄に「intern」が入力されたとき*/
	 if($_POST['pass_delete'] == "intern"){
		/*データの削除*/
		 $id = $_POST['delete'];//削除対象番号に入力された文字
		 $sql_delete = "DELETE FROM mission_5 WHERE id=:id";
		 $stmt_delete = $pdo -> prepare($sql_delete);
		 $stmt_delete -> bindParam(':id', $id, PDO::PARAM_INT);
		 $stmt_delete -> execute();
		
	 }
	/*パスワード欄に「intern」以外が入力されたとき*/
	 else{
		echo "パスワードが違います。";
	 }
 }
 else{
	/*編集対象番号欄に入力されたとき*/
	 if(!empty($_POST['edit'])){
	/*パスワード欄に「intern」が入力されたとき*/
		 if($_POST['pass_edit'] == "intern"){
			/*特定のデータの取得*/
			 $id = $_POST['edit'];//編集対象番号欄に入力された文字
			 $sql_select2 = "SELECT name,comment FROM mission_5 WHERE id=:id";
			 $stmt_select2 = $pdo -> prepare($sql_select2);
			 $stmt_select2 -> bindParam(':id', $id, PDO::PARAM_INT);
			 $stmt_select2 -> execute();
			 $results2 = $stmt_select2 -> fetchAll();
			 foreach($results2 as $row2){
				$edit_name = $row2['name'];
				$edit_comment = $row2['comment'];
			 }
		 }
		/*パスワード欄に「intern」以外が入力されたとき*/
		 else{
			echo "パスワードが違います。";
		 }
	 }
	/*全てのフォームが空のとき*/
	 else{
		echo "何か入力してください。";
	 }
 }
?>

<!DOCTYPE html>
<html>
 <head>
	<meta charset="UFT-8">
	<title>DBを組み合わせた掲示板を作ろう</title>
 </head>

 <body>
	<form method="POST" action="mission_5-1.php">
	<!-- 投稿フォーム -->
	 <input type="text" name="name" placeholder="名前" value="<?php if(!empty($_POST['edit']) && $_POST['pass_edit'] == 'intern'){ echo $edit_name; } ?>"><br>
	 <input type="text" name="comment" placeholder="コメント" value="<?php if(!empty($_POST['edit']) && $_POST['pass_edit'] == 'intern'){ echo $edit_comment; } ?>"><br>
	 <input type="text" name="pass_new" placeholder="パスワード">
	 <input type="hidden" name="edit_sub" value="<?php if(!empty($_POST['edit'])){ echo $_POST['edit']; } ?>">
	 <input type="submit" value="送信"><br><br>

	<!-- 削除フォーム -->
	 <input type="text" name="delete" placeholder="削除対象番号"><br>
	 <input type="text" name="pass_delete" placeholder="パスワード">
	 <input type="submit" value="削除"><br><br>

	<!-- 編集フォーム -->
	 <input type="text" name="edit" placeholder="編集対象番号"><br>
	 <input type="text" name="pass_edit" placeholder="パスワード">
	 <input type="submit" value="編集"><br><br>
	</form>

	<?php
	/*データの表示*/
	 $sql_select = "SELECT * FROM mission_5";
	 $stmt_select = $pdo -> query($sql_select);
	 $results = $stmt_select -> fetchAll();
	 foreach ($results as $row){
		echo $row['id'] . " " . $row['name'] . " " . $row['comment'] . " " . $row['date'] . "<br>";
	 }
	?>

 </body>
</html>