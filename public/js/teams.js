$(document).ready(function() {
    // Функция для загрузки списка команд
    function loadTeams() {
        $.ajax({
            url: "/team/getTeams",
            method: "GET",
            success: function(data) {
                let teams = JSON.parse(data);
                $("#team-list").empty();
                teams.forEach(function(team) {
                    let listItem = `<li>${team.name} (Strength: ${team.strength}) 
                                    <button class="delete-team" data-id="${team.id}">Delete</button></li>`;
                    $("#team-list").append(listItem);
                });
            }
        });
    }

    // Загрузка команд при загрузке страницы
    loadTeams();

    // Добавление команды
    $("#add-team-form").submit(function(e) {
        e.preventDefault();
        let teamName = $("#team-name").val();
        let teamStrength = $("#team-strength").val();

        $.ajax({
            url: "/team/addTeam",
            method: "POST",
            data: { name: teamName, strength: teamStrength },
            success: function() {
                loadTeams();
                $("#team-name").val('');
                $("#team-strength").val('');
            }
        });
    });

    // Удаление команды
    $(document).on("click", ".delete-team", function() {
        let teamId = $(this).data("id");

        $.ajax({
            url: "/team/deleteTeam",
            method: "POST",
            data: {  id: teamId },
            success: function() {
                loadTeams();
            }
        });
    });
});
