<!DOCTYPE html>
<html class="h-full bg-white">
	<head>
		<title>
			<?php if (isset($breadcrumb)): ?>
				<?php foreach (array_reverse($breadcrumb) as $link): ?>
					<?= $this->e($link['title']); ?>
					»
				<?php endforeach; ?>
			<?php endif; ?>
			Backend
		</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
		<link rel="icon" type="image/x-icon" href="data:image/x-icon;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQEAYAAABPYyMiAAAABmJLR0T///////8JWPfcAAAACXBIWXMAAABIAAAASABGyWs+AAAAF0lEQVRIx2NgGAWjYBSMglEwCkbBSAcACBAAAeaR9cIAAAAASUVORK5CYII="/>
		<link href="/assets/backend/backend.css" rel="stylesheet" type="text/css">
		<?= $this->import('/src/Backend/System/Js/Dropdown.tpl.php') ?>
	</head>
	<body
		class="h-full"
		onclick="backend.dropdown.closeall(event)"
	>
		<div>
			<div class="fixed inset-y-0 z-50 flex w-72 flex-col">
				<?= $this->render('/src/Backend/System/Layout/Sidebar.tpl.php') ?>
			</div>
			<div class="pl-72">
				<?= $this->render('/src/Backend/System/Layout/Topbar.tpl.php', get_defined_vars()) ?>
				<main class="py-10">
					<div class="px-14">
                        <?= $this->content() ?>
					</div>
				</main>
			</div>
		</div>
	</body>
</html>


