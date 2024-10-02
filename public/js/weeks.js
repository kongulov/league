$(document).ready(function() {

    let weeks = 0;

    function getLeagueTable(){
        $.ajax({
            url: "/league/getLeagueTable",
            method: "GET",
            success: function(data) {
                let teams = JSON.parse(data);
                $("#league-table tbody").empty();

                teams.forEach(function(team) {
                    let row = `<tr>
                        <td>${team.name}</td>
                        <td>${team.points}</td>
                        <td>${team.wins + team.draws + team.losses}</td>
                        <td>${team.wins}</td>
                        <td>${team.draws}</td>
                        <td>${team.losses}</td>
                        <td>${team.goal_difference}</td>
                    </tr>`;
                    $("#league-table tbody").append(row);
                });
            }
        });
    }

    function getNextMatches(){
        $.ajax({
            url: "/game/getNextMatches",
            method: "GET",
            success: function(data) {
                let games = JSON.parse(data);
                $("#next-week-game-table tbody").empty();

                games.forEach(function(game) {
                    let row = `<tr>
                        <td>${game.home_team}</td>
                        <td>vs</td>
                        <td>${game.away_team}</td>
                    </tr>`;
                    $("#next-week-game-table tbody").append(row);
                });
            }
        });
    }

    function getCurrentWeekResult(){
        $.ajax({
            url: "/game/getCurrentWeekResults",
            method: "GET",
            success: function(data) {
                let games = JSON.parse(data);
                $('#this-week-game-result-table').show();
                $("#this-week-game-result-table tbody").empty();

                games.forEach(function(game) {
                    let row = `<tr>
                        <td>${game.home_team}</td>
                        <td>${game.home_team_goals} - ${game.away_team_goals}</td>
                        <td>${game.away_team}</td>
                    </tr>`;
                    $("#this-week-game-result-table tbody").append(row);
                });
            }
        });
    }

    function predictChampionship(){
        $.ajax({
            url: "/game/predictChampionship",
            method: "GET",
            success: function(data) {
                let teams = JSON.parse(data);
                $("#championship-prediction-table tbody").empty();

                for (let key in teams) {
                    let row = `<tr>
                        <td>${key}</td>
                        <td>${teams[key]} %</td>
                    </tr>`;
                    $("#championship-prediction-table tbody").append(row);
                }
            }
        });
    }

    function initGame(){
        $('#week-count').html(weeks);
        $('#next-week-count').html(weeks + 1);
        getLeagueTable();
        getNextMatches();
    }
    function updateGameData(){
        weeks++;
        $('#week-count').html(weeks);
        $('#next-week-count').html(weeks + 1);
        getLeagueTable();
        getNextMatches();
        getCurrentWeekResult();
        predictChampionship();
    }

    $("#start-game").click(function() {
        $.ajax({
            url: "/game/startNewGame",
            method: "POST",
            success: function(data) {
                $("#start-game").hide();
                $("#next-week").show();
                initGame();
            }
        });
    });


    $("#next-week").click(function() {
        $.ajax({
            url: "/game/nextWeek",
            method: "POST",
            success: function(data) {
                data = JSON.parse(data);

                if (data.success == true) {
                    updateGameData();
                } else {
                    alert('No more weeks!');
                    $("#next-week").hide(); // Скрываем кнопку, когда все недели отыграны
                }
            }
        });
    });
});
