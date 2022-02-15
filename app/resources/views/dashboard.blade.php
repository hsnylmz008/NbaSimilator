<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>NBA Simulator</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
    <script src="main.js"></script>
</head>
<body>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <!------ Include the above in your HEAD tag ---------->

    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>
    <style>
        body {margin:2em;}
    </style>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>NBA Live Matches</h2>
                <table id="live" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Home Team</th>
                            <th>Away Team</th>
                            <th>Home Points</th>
                            <th>Away Points</th>
                            <th>Home Attacks</th>
                            <th>Away Attacks</th>
                            <th>Start Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <h2>NBA Leaderboard</h2>
                <table id="leaderboard" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Team</th>
                            <th>Wins</th>
                            <th>Losses</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-8">
                <h2>NBA Match Weekly Fixtures</h2>
                <table id="fixture" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Home Team</th>
                            <th>Away Team</th>
                            <th>Schedule</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>    
</body>
</html>
<script type="text/javascript">
    $(document).ready(function() {

        //Defining datatables
        var leaderboardTable = $('#leaderboard').DataTable();
        var liveMatchTable = $('#live').DataTable();
        var fixtureTable = $('#fixture').DataTable();

        function updateLeaderBoard()
        {
            $.ajax({
                url: "/api/leaderboard",
                success: function (data) {
                    leaderboardTable.clear().draw();
                    $.each(data.data,  function(key, value) {
                        leaderboardTable.row.add([key + 1, value.name, value.wins, value.losses])
                            .draw()
                            .node();
                    });
                },
                error: function (xhr,status,error) {
                    console.log(error);
                }
            });
        }

        function updateLiveMatches()
        {
            $.ajax({
                url: "/api/live",
                success: function (data) {
                    liveMatchTable.clear().draw();
                    $.each(data.data,  function(key, value) {
                        liveMatchTable.row.add([key + 1, value.home_team, value.away_team, value.home_team_points, value.away_team_points, value.home_team_attacks, value.away_team_attacks, value.start_time])
                            .draw()
                            .node();
                    });
                },
                error: function (xhr,status,error) {
                    console.log(error);
                }
            });
        }
        
        function updateFixture()
        {
            $.ajax({
                url: "/api/fixture",
                success: function (data) {
                    fixtureTable.clear().draw();
                    $.each(data.data,  function(key, value) {
                        fixtureTable.row.add([key + 1, value.home_team, value.away_team, value.schedule, value.status])
                            .draw()
                            .node();
                    });
                },
                error: function (xhr,status,error) {
                    console.log(error);
                }
            });
        }

        updateLeaderBoard();
        updateLiveMatches();
        updateFixture();

        //Update the live matches every 5 seconds
        setInterval(function() {
            updateLiveMatches();
        }, 5000);

        //Update the leaderboard and fixtures every 4 minutes
        setInterval(function() {
            updateLeaderBoard();
            updateFixture();
        }, 250000);

    });
</script>