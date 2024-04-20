<% include Banner %>
<div class="content">
	<div class="container">
		<div class="row">
			<div class="main col-sm-8">
				<% with $Region %>
					<div class="blog-main-image">
						$Photo.SetWidth(750)
					</div>
					<p style="margin-top: 20px;">$Description</p>
				<% end_with %>
			</div>

			<div class="sidebar gray col-sm-4">
				<h2 class="section-title">Regions</h2>
				<ul class="categories subnav">
					<% loop $Regions %>
						<li class="$linkingMode">
							<a class="$linkingMode" href="$Link">$Title</a>
						</li>
					<% end_loop %>
				</ul>
			</div>
		</div>
	</div>
</div>
<!-- END CONTENT -->
