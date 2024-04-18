<!-- BEGIN CONTENT WRAPPER -->
<div class="content">
    <div class="container">
        <div class="row">

            <!-- BEGIN MAIN CONTENT -->
            <div class="main col-sm-8">


                <!-- BEGIN BLOG LISTING -->
                <div class="grid-style1 clearfix">
                    <% loop $Regions %>
                    <div class="item col-md-12"><!-- Set width to 4 columns for grid view mode only -->
                        <div class="image image-large">
                            <a href="$Link">
                                <span class="btn btn-default"><i class="fa fa-file-o"></i> Read More</span>
                            </a>
                            $Photo.CroppedImage(720,255)
                        </div>
                        <div class="info-blog">
                            <h3>
                                <a href="#">$Title</a>
                            </h3>
                            <p>$Description</p>
                        </div>
                    </div>
                    <% end_loop %>

                </div>
                <!-- END BLOG LISTING -->


                <!-- BEGIN PAGINATION -->
                <% if $Regions.MoreThanOnePage %>
                <div class="pagination">
                    <% if $Regions.NotFirstPage %>
                    <ul id="previous">
                        <li><a href="$Regions.PrevLink"><i class="fa fa-chevron-left"></i></a></li>
                    </ul>
                    <% end_if %>
                    <ul class="hidden-xs">
                        <% loop $Regions.PaginationSummary %>
                        <% if $Link %>
                        <li <% if $CurrentBool %>class="active"<% end_if %>>
                            <a href="$Link">$PageNum</a>
                        </li>
                        <% else %>
                        <li>...</li>
                        <% end_if %>
                        <% end_loop %>
                    </ul>
                    <% if $Regions.NotLastPage %>
                    <ul id="next col-xs-6">
                        <li><a href="$Regions.NextLink"><i class="fa fa-chevron-right"></i></a></li>
                    </ul>
                    <% end_if %>
                </div>
                <% end_if %>
                <!-- END PAGINATION -->

            </div>
            <!-- END MAIN CONTENT -->


            <!-- BEGIN SIDEBAR -->
            <div class="sidebar gray col-sm-4">


                <!-- BEGIN LATEST NEWS -->
                <h2 class="section-title">Popular articles</h2>
                <ul class="latest-news">
                    <li class="col-md-12">
                        <div class="image">
                            <a href="blog-detail.html"></a>
                            <img src="http://placehold.it/100x100" alt="" />
                        </div>

                        <ul class="top-info">
                            <li><i class="fa fa-calendar"></i> 30 July 2014</li>
                        </ul>

                        <h4><a href="#">It's all about the Northeast</a></h4>
                    </li>
                    <li class="col-md-12">
                        <div class="image">
                            <a href="blog-detail.html"></a>
                            <img src="http://placehold.it/100x100" alt="" />
                        </div>

                        <ul class="top-info">
                            <li><i class="fa fa-calendar"></i> 20 July 2014</li>
                        </ul>

                        <h4><a href="#">Southwest: Best ever</a></h4>
                    </li>
                    <li class="col-md-12">
                        <div class="image">
                            <a href="blog-detail.html"></a>
                            <img src="http://placehold.it/100x100" alt="" />
                        </div>

                        <ul class="top-info">
                            <li><i class="fa fa-calendar"></i> 10 July 2014</li>
                        </ul>

                        <h4><a href="#">I went to the Northwest and stole from and old lady</a></h4>
                    </li>

                </ul>
                <!-- END LATEST NEWS -->

            </div>
            <!-- END SIDEBAR -->

        </div>
    </div>
</div>
<!-- END CONTENT WRAPPER -->
