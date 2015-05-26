<?php
echo '<div class = "logo">
	<img src="'.$root.'/ACM_logo.png">
	ACM - Association for Computing Machinery
</div>
<ul class="menu">
	<li><a href = "'.$root.'/">Головна</a></li>
	<li><a href = "'.$root.'/tasks'; if (isset($_GET['sort'])) echo '/?sort='.$_GET['sort'];echo'">Завдання</a>
		<ul class="submenu">
			<li><a href = "'.$root.'/tasks/?sort=name">Всі задачі</a></li>
			<li><a href = "'.$root.'/tasks/?sort=topic">Сортувати за темами</a></li>
			<li><a href = "'.$root.'/tasks/addtask">Додати завдачу</a></li>
		</ul>
	</li>
	<li><a href = "'.$root.'/theory">Теорія</a></li>
	<li><a href = "'.$root.'/cource">Онлайн курс</a></li>
	<li><a href = "'.$root.'/contacts">Контакти</a></li>
</ul>';
?>