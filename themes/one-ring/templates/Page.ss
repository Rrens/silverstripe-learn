<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html lang="en"> <!--<![endif]-->

<head>
    <meta charset="utf-8" />

    <!-- Page Title -->
    <% base_tag %>
    $MetaTags(false)

    <title>$Title</title>

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

    <!-- IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
 <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
 <![endif]-->


</head>

<body>
    <!-- BEGIN WRAPPER -->
    <div id="wrapper">

        <!-- BEGIN HEADER -->
        <header id="header">

            <% include TopBar %>
            <% include MainNav %>


        </header>
        <!-- END HEADER -->

       $Layout

        <% include Footer %>

    </div>
    <!-- END WRAPPER -->


</body>

</html>
