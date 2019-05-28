function init(){
  $('.submit').click( submit_form );
  $('#new_stud_add').click( add_sudent );
  $('.edit_comment').on('keyup', update_comment );
  $('.update_mark').change( update_mark );
}

function update_mark(){
  var id = $(this).closest('tr').attr('id');
  var value = $(this).find('option:selected').val();
  $.post(
    "../handler.php",
    {
        action: "update_mark",
        id: id,
        mark: value
    },
  );
}

function update_comment(){
  var id = $(this).closest('tr').attr('id');
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

function add_sudent(){
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

function submit_form(){
  var type = $(this).parent().find('input').attr('id');
  var param = $(this).parent().find('input').val();
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
  $.post(
    "handler.php",
    {
        action: "add_leader",
        title: name
    },
    on_action_answer
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

function on_action_answer(){
  alert('Выполнено');
  document.location.reload();
}

document.addEventListener('DOMContentLoaded', function () {
    init();
});