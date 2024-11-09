<!DOCTYPE html>
<html class="h-full bg-white">
	<head>
		<title>
			{% if breadcrumb is defined %}
				{% for link in breadcrumb|reverse %}
					{{ link.title|t }}
					»
				{% endfor %}
			{% endif %}
			{{ 'Backend'|t }}
		</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
		<link rel="icon" type="image/x-icon" href="data:image/x-icon;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQEAYAAABPYyMiAAAABmJLR0T///////8JWPfcAAAACXBIWXMAAABIAAAASABGyWs+AAAAF0lEQVRIx2NgGAWjYBSMglEwCkbBSAcACBAAAeaR9cIAAAAASUVORK5CYII="/>
		<link href="/assets/backend/backend.css" rel="stylesheet" type="text/css">
		{{ include_once('@backend/system/js/dropdown.tpl.php') }}
	</head>
	<body
		class="h-full"
		onclick="backend.dropdown.closeall(event)"
	>
		<div>
			<div class="fixed inset-y-0 z-50 flex w-72 flex-col">
				{{ include('@backend/system/layout/sidebar.tpl.php') }}
			</div>
			<div class="pl-72">
				{{ include('@backend/system/layout/topbar.tpl.php') }}
				<main class="py-10">
					<div class="px-14">
                        {{ block('content') }}
					</div>
				</main>
			</div>
		</div>
	</body>
</html>
