<% include Banner %>
<div class="content">
	<div class="container">
		<div class="row">
			<div class="main col-sm-8">
				<% with $Property %>

					<div class="blog-main-image">
						$PrimaryPhoto.SetWidth(750)
					</div>
					<p style="margin-top: 20px;">City: $City</p>
					<p style="margin-top: 20px;">Address: $Address</p>
					<p style="margin-top: 20px;">Type: $PropertyType.Title</p>
					<p style="margin-top: 20px;">Transaction Type: $TransactionType</p>
					<p style="margin-top: 20px;">Description: <br> $Description</p>
				<% end_with %>
			</div>

			<div class="sidebar gray col-sm-4">
				<h2 class="section-title">Agent</h2>
                <% with $Property %>
                    <% loop $Agents %>
                        <center style="margin-bottom: 20px;">
                            $Photo.SetWidth(150)
                            <p style="margin-top: 10px;">Name: $Name</p>
                            <p style="margin-top: 10px;">About: $About</p>
                            <a href="https://wa.me/$Whatsapp" target="_blank" class="btn btn-success" style="margin-top: 10px;">Chat</a>
                        </center>
                    <% end_loop %>
                <% end_with %>
			</div>

            <div class="sidebar gray col-sm-4">
				<h2 class="section-title">Facilities</h2>
                <%-- <% with $Facilities %>
                    <% for $Facilities in $item %>
                        <p style="margin-top: 10px;">$Title</p>
                    <% end_for %>
                <% end_with %> --%>
				<ul class="categories subnav">
					<% with $Property %>
                        <% loop $Facilities %>
                            <li>
                                <a href="javascript:void(0)">$Photo.SetWidth(14) $Title</a>
                            </li>
                        <% end_loop %>
                    <% end_with %>
				</ul>
			</div>
		</div>
	</div>
</div>
<!-- END CONTENT -->
