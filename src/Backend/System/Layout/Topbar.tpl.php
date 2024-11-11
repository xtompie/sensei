<?= $this->import('/src/Backend/System/Js/Visible.tpl.php') ?>
<?= $this->import('/src/Backend/System/Js/Dropdown.tpl.php') ?>

<div class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-200 bg-white shadow-sm px-8">

	<div class="flex flex-1 self-stretch justify-between gap-x-6">
		<?= $this->render('/src/Backend/System/Layout/Breadcrumb.tpl.php', [
		    'breadcrumb' => $breadcrumb,
		]) ?>
		<div
			class="flex items-center gap-x-6">
			<!-- Profile dropdown -->
			<div
				backend-dropdown-space
				class="relative"
			>
				<button
					type="button"
					class="-m-1.5 flex items-center p-1.5"
					aria-expanded="false"
					aria-haspopup="true"
					onclick="backend.dropdown.toggle(this, event)"
				>
					<span class="inline-block h-8 w-8 overflow-hidden rounded-full bg-gray-100">
						<svg class="h-full w-full text-gray-300" fill="currentColor" viewbox="0 0 24 24">
							<path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z"/>
						</svg>
					</span>
				</button>

				<div
					backend-dropdown-panel
					style="display: none;"
					class="opacity-100 absolute right-0 z-10 mt-2.5 w-32 origin-top-right rounded-md bg-white py-2 shadow-lg ring-1 ring-gray-900/5 focus:outline-none"
					role="menu"
					aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1"
				>
					<a href="#" class="block px-3 py-1 text-sm leading-6 text-gray-900 hover:bg-gray-200" role="menuitem" tabindex="-1">Your profile</a>
					<a href="#" class="block px-3 py-1 text-sm leading-6 text-gray-900 hover:bg-gray-200" role="menuitem" tabindex="-1">Sign out</a>
				</div>
			</div>
		</div>
	</div>
</div>
