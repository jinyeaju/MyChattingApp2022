//회원가입 창 뜨는 순간 아이디 입력 상자에 포커싱한다.
document.register_form.id.focus();
//하단의 "회원가입"버튼 클릭시 각 입력상자의 유효성 검사를 진행한다.
const check_input = () => {
    if(!document.register_form.id.value){
        alert("아이디를 입력해주세요.");
        document.register_form.id.focus();
        return;
    }
    if(!document.register_form.pass.value){
        alert("패스워드를 입력해주세요.");
        document.register_form.pass.focus();
        return;
    }
    if(!document.register_form.pass_confirm.value){
        alert("패스워드를 재입력해주세요.");
        document.register_form.pass_confirm.focus();
        return;
    }
    if(!document.register_form.name.value){
        alert("이름을 입력해주세요.");
        document.register_form.name.focus();
        return;
    }
    if(!document.register_form.email.value){
        alert("이메일을 입력해주세요.");
        document.register_form.email.focus();
        return;
    }
    if(document.register_form.pass.value != document.register_form.pass_confirm.value){
        alert("패스워드와 패스워드 확인의 입력값이 일치하지 않습니다. 재입력해주세요.");
        document.register_form.pass.focus();
        return;
    }
    document.register_form.submit();
}

$(document).ready(function(){
    
});