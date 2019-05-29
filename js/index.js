function init(){  
  $('#new_stud_add').click( add_student );
  $('.submit').click( submit_form );
  $('.student_mark').change( update_mark );
  $('#choose_group').change( load_students_list );
  $('#_comment').on( 'keyup', update_comment );
  $('.login_btn').click( try_auth );
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

function load_students_list(){
  var value = $(this).find('option:selected').val();
  document.location.href= location + "?group_id=" + value + "&action=load_students"
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
        comment: comment
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