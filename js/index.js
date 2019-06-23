function init(){  
  $('#new_stud_add').click( add_student );
  $('.submit').click( submit_form );
  $('.student_mark').change( update_mark );
  $('#_comment').on( 'keyup', update_comment );
  $('.login_btn').click( try_auth );
  $('#logout').click( logout );
  $('#comission').change( comission_handler );
  $('.comission_submit').click( comission_submit );
  $('#choose_date').change( date_changed );
  $('#choose_group').change( group_changed );
  clock();
}

function comission_handler(){
  var selected = $(this).find('option:selected');
  var value = selected.val();
  var text = selected.text();
  if( value == 'create_new' ){
    $('#comission').hide();
    $('#comission_field').removeClass('hidden');
    $('.point_block').removeClass('hidden');
  }else{
    $('#comission').hide();
    $('#comission_field').removeClass('hidden');
    $('#comission_field').val( text );
    $('.point_block').removeClass('hidden');
    if( selected.hasClass('leader_pck') ){
      $('#is_main').attr( 'checked', true );
    }
  }
}

function date_changed(){
  var group = $('#choose_group').find('option:selected').val();
  if( group == '0'){
    $('#choose_group').css('border', 'solid 1px red');
    return;
  }
  load_students();
}

function group_changed(){
  var date = $('#choose_date').find('option:selected').val();
  if( date != '0'){
    load_students();
  }
}

function load_students(){
  var group = $('#choose_group').find('option:selected').val();
  var group_title = $('#choose_group').find('option:selected').text();
  var date = $('#choose_date').find('option:selected').val();
  document.location.href= location + "?group_id=" + group + "&group_title=" + group_title + "&date=" + date + "&action=load_students"
}

function comission_submit(){
  var selector = $('#comission').find('option:selected').val();
  var input = $('#comission_field').val();
  var modified_input = input.replace(/[^a-яА-ЯЁЪёъйЙ]/ig,"");
  if( input !== modified_input ){
    $('#comission_field').val(modified_input);
    alert('Были удалены запрещенные символы. Проверьте правильность данных и повторите отправку формы.')
    return;
  }
  var type = 0;
  if( $("#is_main").prop("checked") ){
    type = 1;
  }
  if( selector == 'create_new' ){
    $.post(
      "handler.php",
      {
          action: "add_comm",
          title: input,
          type: type
      },
      on_action_answer
    );
  }else{

    if( input == '' ){
      $.post(
        "handler.php",
        {
            action: "delete_comm",
            id: selector
        },
        on_action_answer
      );
      return;
    }

    $.post(
      "handler.php",
      {
          action: "update_comm",
          title: input,
          type: type,
          id: selector
      },
      on_action_answer
    );


  }
}

function clock() {
    var d = new Date();
    var month_num = d.getMonth()
    var day = d.getDate();
    var hours = d.getHours();
    var minutes = d.getMinutes();
    var seconds = d.getSeconds();
    
    var month=new Array("января", "февраля", "марта", "апреля", "мая", "июня",
    "июля", "августа", "сентября", "октября", "ноября", "декабря");
    
    if (day <= 9) day = "0" + day;
    if (hours <= 9) hours = "0" + hours;
    if (minutes <= 9) minutes = "0" + minutes;
    if (seconds <= 9) seconds = "0" + seconds;
    
    date_time = "Сегодня - " + day + " " + month[month_num] + " " + d.getFullYear() +
    " г.&nbsp;&nbsp;&nbsp;<br/>Текущее время - "+ hours + ":" + minutes + ":" + seconds;
    if (document.layers) {
    document.layers.doc_time.document.write(date_time);
    document.layers.doc_time.document.close();
    }
    else document.getElementById("doc_time").innerHTML = date_time;
    setTimeout("clock()", 1000);
}

function logout(){
  $.post(
    "handler.php",
    {
        action: "logout"
    },
    on_action_answer
  );
}

function try_auth(){
  var login = $('input[name=username]').val();
  var password = $('input[name=password]').val();
  if( login == '' || password == '' ){
    alert('Заполните поля!');
    return;
  }
  $.post(
    "handler.php",
    {
        action: "login",
        login: login,
        password: password
    },
    reload
  );
}

function update_mark(){
  var id = $('.student_block').attr('id');
  var value = $(this).find('option:selected').val();
  $.post(
    "handler.php",
    {
        action: "update_mark",
        id: id,
        mark: value
    },
  );
}

function update_comment(){
  var id = $('.student_block').attr('id');
  var text = $(this).val();
  $.post(
    "handler.php",
    {
        action: "edit_comment",
        id: id,
        text: text
    }
  );
}

function submit_form(){
  var type = $(this).parent().attr('id');
  var param = $(this).parent().find('input').val();
  if( param == '' || !param ){
    alert('Заполните поле!');
    return;
  }
  switch (type) {
    case 'new_leader':
      put_leader( param )
      break;
    case 'new_group':
      put_group( param )
      break;
    default:
      break;
  }
}

function put_leader( name ){
  var parts = name.split(' ');
  $.post(
    "handler.php",
    {
        action: "add_leader",
        title: name,
        last_name: parts[0]
    },
    on_leader_created
  );
}

function put_group( title ){
  $.post(
    "handler.php",
    {
        action: "add_group",
        title: title
    },
    on_action_answer
  );
}

function add_student(){
  var first_name = $('#new_student_f_name').val();
  var last_name = $('#new_student_l_name').val();
  var surname = $('#new_student_s_name').val();
  var group = $('#new_student_group').find('option:selected').val();
  var leader = $('#new_student_leader').find('option:selected').val();
  var comment = $('#new_student_comment').val();
  var theme = $('#new_student_theme').val();
  var date = $('#new_student_date').val();
  if( first_name == '' || last_name == '' || surname == '' ){
    alert('Заполните все поля!');
    return;
  }

  $.post(
    "handler.php",
    {
        action: "add_student",
        first_name: first_name,
        last_name: last_name,
        surname: surname,
        group: group,
        theme: theme,
        leader: leader,
        comment: comment,
        date: date
    },
    on_action_answer
  );
}

function on_leader_created(data){
  var obj = $.parseJSON(data);
  var login = obj.login;
  var password = obj.password;
  var noty = "Запишите данные сотрудника. Логин: " + login + " , пароль: " + password;
  alert(noty);
  document.location.reload();
}

function reload( data ){
  var obj = $.parseJSON(data);
  if( obj.result == 'not_found' ){
    alert('Ошибка! Такого пользователя не существует. ');
    $('input[name=username]').val('');
    $('input[name=password]').val('');
    return;
  }
  if( obj.result == 'wrong_password' ){
    alert('Ошибка! Неверный пароль.');
    $('input[name=password]').val('');
    return;
  }
  document.location.reload();
}

function on_action_answer(){
  alert('Выполнено');
  document.location.reload();
}

document.addEventListener('DOMContentLoaded', function () {
    init();
});