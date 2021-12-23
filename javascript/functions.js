jQuery.extend(jQuery.validator.messages, {
    required: "Este campo es requerido.",
    //remote: "Please fix this field.",
    email: "Ingresa un correo válido.",
    //url: "Please enter a valid URL.",
    date: "Ingresa una fecha válida.",
    //dateISO: "Please enter a valid date (ISO).",
    //number: "Please enter a valid number.",
    //digits: "Please enter only digits.",
    creditcard: "Please enter a valid credit card number.",
    //equalTo: "Please enter the same value again.",
    //accept: "Please enter a value with a valid extension.",
    maxlength: jQuery.validator.format("El campo puede tener máximo {0} caracteres."),
    minlength: jQuery.validator.format("Ingresa al menos {0} caracteres."),
    //rangelength: jQuery.validator.format("Please enter a value between {0} and {1} characters long."),
    //range: jQuery.validator.format("Please enter a value between {0} and {1}."),
    max: jQuery.validator.format("Ingresa un valor menor o igual a {0}."),
    min: jQuery.validator.format("Ingresa un valor mayor o igual a  {0}.")
});

function hideimg(x,y,z) {
  if (x.style.display === "none") {
    x.style.display = "block";
    y.style.display = "none";
    z.style.display = "none";    
  } else {
    x.style.display = "none";
    y.style.display = "block";
    z.style.display = "block";        
  }
}

function hidemenu(){
    var x = document.getElementById("menu");
    var y = document.getElementById("showmenu");
  if (x.style.display === "block") {
    x.style.display = "none";
    y.style.display = "block";

    document.getElementById("content").style.marginLeft = "3%";
    document.getElementById("content").style.width = "97%";
    document.getElementById("header2").style.marginLeft = "-10%";  
  }
  $("#frente").show();
}

function showmenu(){
    var x = document.getElementById("menu");
    var y = document.getElementById("showmenu");    
  if (x.style.display === "none") {
    x.style.display = "block";
    y.style.display = "none";

    document.getElementById("content").style.marginLeft = "17.55%";
    document.getElementById("content").style.width = "82.4%";
    document.getElementById("header2").style.marginLeft = "0%";    
  } 
}

function dashboard() {
var ori = document.getElementById("orientacion");
var selec = ori.options[ori.selectedIndex].text;
var cumpl = document.getElementById("cumpl");
var dear = document.getElementById("dear");
var tipd = document.getElementById("tipd");
var compe = document.getElementById("compe");

if (selec == "Vertical") {
    cumpl.setAttribute('style','width: 100%');
    dear.setAttribute('style','width: 100%; margin-top: 0%;');
    tipd.setAttribute('style','width: 100%');  
    compe.setAttribute('style','width: 100%; margin-top: 0%;');
  }

if (selec == "Horizontal") {
    cumpl.setAttribute('style','width: 50%');
    dear.setAttribute('style','width: 50%; margin-top: -60.1%');
    tipd.setAttribute('style','width: 50%');  
    compe.setAttribute('style','width: 50%; margin-top: -60.1%');

    if($("#decl").is(':checked') && $("#ara").is(':unchecked') && $("#tip").is(':checked') && $("#comp").is(':unchecked'))
    {
       tipd.setAttribute('style','width: 50%; float:right; margin-top: -60.1%');  
    }

    if($("#decl").is(':checked') && $("#ara").is(':checked') && $("#tip").is(':unchecked') && $("#comp").is(':checked'))
    {
       compe.setAttribute('style','width: 100%; margin-top: 0%; float:left;');
    }
    if($("#decl").is(':checked') && $("#ara").is(':checked') && $("#tip").is(':checked') && $("#comp").is(':unchecked'))
    {
      tipd.setAttribute('style','width: 100%'); 
    }
    if($("#decl").is(':checked') && $("#ara").is(':unchecked') && $("#tip").is(':checked') && $("#comp").is(':checked'))
    {
      tipd.setAttribute('style','width: 50%; float:right; margin-top: -60.1%'); 
      compe.setAttribute('style','width: 100%; margin-top: 0%');
    }
    if($("#decl").is(':unchecked') && $("#ara").is(':checked') && $("#tip").is(':checked') && $("#comp").is(':checked'))
    {
      dear.setAttribute('style','width: 50%; margin-top: 0%; float:left;');
      tipd.setAttribute('style','width: 50%; float:right; margin-top: -60.1%'); 
      compe.setAttribute('style','width: 100%; margin-top: 0%');
    }
    if($("#decl").is(':unchecked') && $("#ara").is(':checked') && $("#tip").is(':checked') && $("#comp").is(':unchecked'))
    {
      dear.setAttribute('style','width: 50%; margin-top: 0%; float:left;');
      tipd.setAttribute('style','width: 50%; float:right; margin-top: -60.1%'); 
    }
    if($("#decl").is(':unchecked') && $("#ara").is(':checked') && $("#tip").is(':unchecked') && $("#comp").is(':checked'))
    {
      dear.setAttribute('style','width: 50%; margin-top: 0%; float:left;');
      tipd.setAttribute('style','width: 50%; float:right; margin-top: 0%'); 
    }
    if($("#decl").is(':checked') && $("#ara").is(':unchecked') && $("#tip").is(':unchecked') && $("#comp").is(':unchecked'))
    {
      cumpl.setAttribute('style','width: 100%;');
    }
    if($("#decl").is(':unchecked') && $("#ara").is(':checked') && $("#tip").is(':unchecked') && $("#comp").is(':unchecked'))
    {
      dear.setAttribute('style','width: 100%; margin-top: 0%;');
    }
    if($("#decl").is(':unchecked') && $("#ara").is(':unchecked') && $("#tip").is(':checked') && $("#comp").is(':unchecked'))
    {
      tipd.setAttribute('style','width: 100%;');
    }
    if($("#decl").is(':unchecked') && $("#ara").is(':unchecked') && $("#tip").is(':unchecked') && $("#comp").is(':checked'))
    {
      compe.setAttribute('style','width: 100%; margin-top: 0%;');
    }
}


if($("#decl").is(':checked')) {  
          $("#cumpl").show();
      }
      else{
        $("#cumpl").hide();
      }
if($("#ara").is(':checked')) {  
          $("#dear").show();
      }
      else {
        $("#dear").hide();
      }
if($("#tip").is(':checked')) {  
          $("#tipd").show();
      }
      else {
       $("#tipd").hide();
      }
 if($("#comp").is(':checked')) {  
          $("#compe").show();
      }
      else {
         $("#compe").hide();
      }
}

function valida(evt,datatype){
  var theEvent = evt || window.event;
  if (theEvent.type === 'paste') {
      key = event.clipboardData.getData('text/plain');
  } else {
      var key = theEvent.keyCode || theEvent.which;
      key = String.fromCharCode(key);
  }
  var regex="";
  if(datatype=='int'){regex= /[0-9]/;}
  if(datatype=='float'){regex = /[0-9]|\./;}
  if(datatype=='upper'){regex= /[A-Za-zÑñÁáÉéÍíÓóÚú\s]/;}
  if(datatype=='clave'){regex= /[A-Za-z0-9]/;}
  if(datatype=='string'){regex= /[A-Za-zÑñ0-9\s]/;}
  if( !regex.test(key) ) {
    theEvent.returnValue = false;
    if(theEvent.preventDefault) theEvent.preventDefault();
  }
}

function valida_curp(node,datatype,id){
  cadena=node.value;
  //console.log(node.value);
  var regex="";
  if(datatype=='curp'){
    regex=/^([A-Z][AEIOUX][A-Z]{2}\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])[HM](?:AS|B[CS]|C[CLMSH]|D[FG]|G[TR]|HG|JC|M[CNS]|N[ETL]|OC|PL|Q[TR]|S[PLR]|T[CSL]|VZ|YN|ZS)[B-DF-HJ-NP-TV-Z]{3}[A-Z\d])(\d)$/;
  }
  if(datatype=='rfc'){
    regex=/^([A-ZÑ&]{3,4}) ?(?:- ?)?(\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])) ?(?:- ?)?([A-Z\d]{2})([A\d])$/;
  }
  if(regex.test(cadena) ) {
    $("#"+id).text("");
  }
  else{
    $("#"+node.id).val("");
    $("#"+id).text("El formato es incorrecto.");
  }
}
function valida_rfc(value,datatype,id){
  
  var regex="";
  if(datatype=='rfc'){
    regex=/^([A-ZÑ&]{3,4}) ?(?:- ?)?(\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])) ?(?:- ?)?([A-Z\d]{2})([A\d])$/;
  }
  //console.log(cadena+" "+regex.test(cadena));
  if(regex.test(value) ) {
    $("#"+id).text("");
  }
  else{
    $("#rfc_empleado").val("");
    $("#hc_empleado").val("");
    $("#"+id).text("El formato del RFC es incorrecto.");
  }
}

function tipo_participacion(){
  if($("#tipo_participacion").val()=="O"){
     $("#div_part").show();       
   }
   else{
     $("#div_part").hide();    
   }

}

function menu_dinamico(id,arb,abj){
  var menujs = document.getElementById(id);
  var imgarb = document.getElementById(arb);
  var imgabj = document.getElementById(abj)

  if (menujs.style.display === "none")
   {
    $(menujs).show();
    }
    else 
    {
    $(menujs).hide();
    }

    if (imgarb.style.display === "none")
    {
    imgabj.style.display = "none";
    imgarb.style.display = "block";
    }
    else 
    {
    imgabj.style.display = "block";
    imgarb.style.display = "none";
    }
}

function menuopc(idhjo,idpad,idabu){
  var subme1 = document.getElementById(idhjo);
  var menus = document.getElementById(idpad);
  var menus1 = document.getElementById(idabu);
  var tex1 = subme1.getElementsByTagName("P")[0];

  if (menus.style.display === "none")
   {
    menus.style.display = "block";
    menus1.style.display = "block";
    subme1.style.backgroundColor="#7f7f7f";
    tex1.style.color= "white";    
    }
}

function menuopc_v2(id){
  var decl = document.getElementById(id);

  if (decl.style.display === "none")
  {
    decl.style.display = "block";
    }
}

function openDialog(id) {
        $('#overlay').fadeIn('fast');
        $('#'+id).fadeIn('fast');
}
function openDialog2() {
        $('#popup').fadeOut('fast');
        $('#popup2').fadeIn('fast');
}
function openDialog3() {
        $('#overlay').fadeIn('fast');
        $('#popup3').fadeIn('fast');
}
 
function openDialog5() {
        $('#popup4').fadeOut('fast');
        $('#overlay').fadeIn('fast');
        $('#popup5').fadeIn('fast');
}

function closeDialog(id) {
        $('#overlay').fadeOut('fast');
        $('#'+id).fadeOut('fast');
}

function guardaraviso(id){
    var texto=document.getElementById("aviso_editado_"+id).textContent;
    //console.log(a);
    $.ajax({
    type: 'post',
    url: '../models/aviso-update.php',
    data: {'id':id,'texto':texto},
    success: function (data) {
      //console.log(data);
      $("#overlay").show();
      $("#aceptar-cambio").show();
      $("#mensaje").empty();
      $("#mensaje").append(data);
    }
  });
}
function lista_municipios(municipio){
  var estado=document.getElementById("estado").value;
  $.ajax({
    type: 'post',
    url: '../models/codigos-postales.php',
    data: {"estado":estado},
    success: function (data) {
      $("#municipio").empty();
      $("#municipio").append(data);
      $("#municipio").val(municipio);
    }
  });
}
function lista_colonias(colonia){
  var cp=document.getElementById("cp").value;
  $.ajax({
    type: 'post',
    url: '../models/codigos-postales.php',
    data: {"codigopostal":cp},
    success: function (data) {
      $("#colonia-select").empty();
      $("#colonia-select").append(data);
      $("#colonia-select").val(colonia);
    }
  });
}
function ubicacion_m(){
    $("#pais").val("MEX");
    $("#buscar-direccion").show();
    $("#colonia-div").show();
    $("#colonia2-div").hide();
    $("#municipio-div").show();
    $("#estado-div").show();
    $("#estado2-div").hide();
    $("#estado2").val("");
    $("#cp").attr("required",true);  
    $("#calle").attr("required",true);  
    $("#num_exterior").attr("required",true);  
    $(".ast").show();
}
function ubicacion_e(){
    $("#buscar-direccion").hide();
    $("#municipio-div").hide();
    $("#estado-div").hide();
    $("#estado2-div").show();
    $("#colonia-div").show();
    $("#colonia2-div").hide();
    $("#estado").val("");
    $("#municipio").val("");
    $("#colonia-select").val("");
    $("#cp").removeAttr("required");  
    $("#calle").removeAttr("required");  
    $("#num_exterior").removeAttr("required");  
    $(".ast").hide();
}
function ubicacion_m1(){
    $("#01pais").css("display","none");
    $("#01pais2").css("display","none");
    $("#ent_fed").css("display","inline-block");
    $("#ent_fed2").css("display","inline-block");
    $("#pais").val(1);
/*
    $("#pais2").val(1);
*/
}
function ubicacion_e1(){
    $("#01pais").css("display","inline-block");
    $("#01pais2").css("display","inline-block");
    $("#ent_fed").css("display","none");
    $("#ent_fed2").css("display","none");
    $("#estado").val("");
    $("#estado3").val("");
    $("#pais").val("");
    //$("#pais2").val("");
}
function domicilio_s(){
  var rfc=document.getElementsByName("rfc")[0].value;
  var tipo_decl=document.getElementsByName("declaracion")[0].value;
  var ejercicio=document.getElementsByName("ejercicio")[0].value;

  $.ajax({
    type: 'post',
    url: '../models/domicilio-declarante.php',
    data: {'rfc':rfc,'tipo_decl':tipo_decl,'ejercicio':ejercicio},
    success: function (data) {
      if(data){
        //console.log(data);
        var datos=JSON.parse(data);
        $('#residencia').val(datos.ubicacion);
        residencia();
        $('#pais').val(datos.pais);
        $('#cp').val(datos.codigopostal);
        if($('#residencia').val()=="M"){
          $('#estado').val(datos.estado);
          lista_municipios(datos.municipio);
        }
        else{
          $('#estado2').val(datos.estado_desc);
        }
        $('#colonia').val(datos.colonia_desc);
        $('#calle').val(datos.calle);
        $('#num_exterior').val(datos.num_exterior);
        $('#num_interior').val(datos.num_interior);
      }
    }
  });
}
function domicilio_n(){
    $('#residencia').val("");
    $('#pais').val("");
    $('#cp').val("");
    $('#estado').val("");
    $('#municipio').val("");
    $('#estado2').val("");
    $('#colonia').val("");
    $('#calle').val("");
    $('#num_exterior').val("");
    $('#num_interior').val("");
}

function remuneracion_s(){
  $("#monto_mensual_div").show();
}
function remuneracion_n(){
  $("#monto_mensual_div").hide();
}
function recepcion_m(){
  $("#01apoyo").hide();
}
function recepcion_e(){
  $("#01apoyo").show();
}
function residencia(){
  if($("#residencia").val()=="M"){
    ubicacion_m();
  }
  else{
    ubicacion_e();
  }
}
function efectos()
{
    $("#edo_civil").on("change",function(){
      estado_civil();
    });

  $("#regimen_mat").on("change",function(){
    regimen_matrimonial();
  });
  $("#observaciones").on("keyup",function(){
    $("#contador").html(document.getElementById("observaciones").value.length);
  });
  $("#dommex").on("click",function(){
    ubicacion_m();
  });
  $("#domext").on("click",function(){
    ubicacion_e();
    $("#pais").val("");
  });
  $("#nivel").on("change",function(){
    if($("#nivel").val()=="P" || $("#nivel").val()=="S" || $("#nivel").val()=="B"){
      $("#area-conocimiento").hide();
      $("#carrera").val("");
    }
    else{
     $("#area-conocimiento").show(); 
    }
  });
  $("#residencia").on("change",function(){
    $("#pais").val("");
    residencia();
  });

  $("#ente").on("change",function(){
    ente();
  });
  $("#sector").on("change",function(){
    $("#ente").val("");
    $("#otro_ente").val("");
    $("#area").val("");
    $("#puesto").val("");
    $("#ente2").val("");
    $("#area2").val("");
    $("#puesto2").val("");
    $("#otro_ambito").val("");
    $("#otro_sector").val("");
    $("#rfc_empresa").val("");
    $("#sector-pert").val("");
    $("#nivel_gobierno").val("");
    $("#ambito_publico").val("");
    $("#funcion_principal").val("");
    $("#fecha_ingreso").val("");
    $("#fecha_egreso").val("");
    $("#sueldo_mensual").val("");
    //$("#ente").val("");

    sector_laboral();
  });
  $("#sector-pert").on("change",function(){
    otro_sector();
  });
  $("#instrumento").on("change",function(){
    tipo_instrumento();
  });
  $("#relacion_depend").on("change",function(){
    otra_relacion();
  });
  $("#tipo_inmueble").on("change",function(){
    otro_inmueble();
  });
  $("#relacion").on("change",function(){
    otra_relacion2();
  });
  $("#causa_baja").on("change",function(){
    causa_baja();
  });
  $("input[type=radio][name=servidor]").on("change",function(){
    
    if($(this).val()=="S"){
      $(".servidor-content").show();
    }
    else{
      $(".servidor-content").hide();      
    }
  });
  $("#tipo_vehiculo").on("change",function(){
    otro_vehiculo();
  });
  $("#tipo_mueble").on("change",function(){
    otro_mueble();
  });
  $("#tercero").on("change",function(){
    if($(this).val()=="N"){
      $(".tercero-options").hide();
    }
    else{
      $(".tercero-options").show();
    }
  });
  $("#transmisor").on("change",function(){
    if($(this).val()=="N"){
      $(".transmisor-options").hide();
      $(".transmisor-options :input").val("");
      $("#otra-rel").hide();
      $("#otra-rel :input").val("");
    }
    else{
      $(".transmisor-options").show();
    }
  });
  $("#tipo_inversion").on("change",function(){
    tipo_inversion();
  });
  $("input[type=radio][name=localidad-inver]").on("change",function(){
    
    if($(this).val()=="M"){
      $("#pais-inst").hide();
      $("#rfc-inst").show();
    }
    else{
     $("#rfc-inst").hide(); 
      $("#pais-inst").show();      
    }
  });
  $("#tipo_adeudo").on("change",function(){
    tipo_adeudo();
  });
  $("input[type=radio][name=localidad-adeudo]").on("change",function(){    
    if($(this).val()=="M"){
      $("#pais-inst").hide();
    }
    else{
      $("#pais-inst").show();      
    }
  });
  $("#tipo_comodato").on("change",comodato);
  $("#password2").on("keyup",function(){
    if($("#password2").val() != $("#password").val()) {
      this.setCustomValidity("Las contraseñas no coinciden.");
    } else {
      this.setCustomValidity('');
    }
  });
  $("#email2").on("blur",function(){
    if($("#email2").val() != $("#email1").val()) {
      //console.log("sí");
      $("#confirmar_correo").show();
      this.setCustomValidity("Los correos no coinciden.");
    } else {
      $("#confirmar_correo").hide();
      this.setCustomValidity('');
    }
  });
  $("#email1").on("blur",function(){
    if($("#email2").val() != $("#email1").val()) {
      //console.log("sí");
      $("#confirmar_correo").show();
      this.setCustomValidity("Los correos no coinciden.");
    } else {
      $("#confirmar_correo").hide();
      this.setCustomValidity('');
    }
  });
  $("#tipo_participacion").on("change",function(){
    tipo_participacion();
  });

$("#01mexico").on("click",function(){
    $("#pais2").val("");
    ubicacion_m1();
  });

$("#01extranjero").on("click",function(){
    $("#pais2").val("");
    ubicacion_e1();
  });

$("#01si").on("click",function(){
    remuneracion_s();
  });
$("#01no").on("click",function(){
    remuneracion_n();
  });
$("#dom_s").on("click",function(){
    //$("#pais").val("México");
    domicilio_s();
  });
$("#dom_n").on("click",function(){
    //$("#pais").val("México");
    domicilio_n();
  });
$("#tipo_institucion").on("change",function(){
  tipo_institucion();
});
$("#beneficiario").on("change",function(){
  otro_beneficiario();
});
$("#tipo_apoyo").on("change",function(){
  otro_apoyo();
});

$("#01especie").on("click",function(){
    recepcion_e();
  });
$("#01monetario").on("click",function(){
    $("#01apoyo").hide();
  });
$("#tipo_beneficio").on("change",function(){
  otro_beneficio();
});

$("#editar-info").on("click",function(){
    $(".user-edit").css("display","inline-block");
    $(".user-edited").css("display","none");
    $("#guardar-info").css("display","inline-block");
    $("#cambiar-foto").css("display","inline-block");
    $(this).css("display","none");
});

$("#curp").on("change",function(){
    valida_curp(this,"curp","aviso_curp");
});
$("#rfc_tercero").on("change",function(){
    valida_curp(this,"rfc","aviso_rfc_tercero");
});
$("#rfc_transmisor").on("change",function(){
    valida_curp(this,"rfc","aviso_rfc_transmisor");
});
$("#rfc_institucion").on("change",function(){
    valida_curp(this,"rfc","aviso_rfc_institucion");
});
$("#rfc_dueno").on("change",function(){
    valida_curp(this,"rfc","aviso_rfc_dueno");
});
$("#rfc_pareja").on("change",function(){
    valida_curp(this,"rfc","aviso_rfc_pareja");
});
$("#rfc_dependiente").on("change",function(){
    valida_curp(this,"rfc","aviso_rfc_dependiente");
});
$("#curp_dependiente").on("change",function(){
    valida_curp(this,"curp","aviso_curp_dependiente");
});
$("#rfc_empresa").on("change",function(){
    valida_curp(this,"rfc","aviso_rfc_empresa");
});
$("#rfc_inst").on("change",function(){
    valida_curp(this,"rfc","aviso_rfc_inst");
});
$("#rfc_repre").on("change",function(){
    valida_curp(this,"rfc","aviso_rfc_repre");
});
$("#rfc_cliente").on("change",function(){
    valida_curp(this,"rfc","aviso_rfc_cliente");
});
$("#rfc_otorga").on("change",function(){
    valida_curp(this,"rfc","aviso_rfc_otorga");
});
$("#rfc_fideicomiso").on("change",function(){
    valida_curp(this,"rfc","aviso_rfc_fideicomiso");
});
$("#rfc_fideicomitente").on("change",function(){
    valida_curp(this,"rfc","aviso_rfc_fideicomitente");
});
$("#rfc_fiduciario").on("change",function(){
    valida_curp(this,"rfc","aviso_rfc_fiduciario");
});
$("#rfc_fideicomisario").on("change",function(){
    valida_curp(this,"rfc","aviso_rfc_fideicomisario");
});
$("#rfc_empleado").on("change",function(){
  if($("#hc_empleado").val()!=""){
    var value=$("#rfc_empleado").val()+$("#hc_empleado").val();
    //console.log(value);
    valida_rfc(value,"rfc","aviso_rfc");
  }
});
$("#hc_empleado").on("change",function(){
  if($("#rfc_empleado").val()!=""){
    var value=$("#rfc_empleado").val()+$("#hc_empleado").val();
    //console.log(value);
    valida_rfc(value,"rfc","aviso_rfc");
  }
});
$("#rfc_declarante").on("change",function(){
    valida_curp(this,"rfc","aviso_rfc_declarante");
});

$("#cambio-pass").on("click",function(){
    $("#overlay").show();
    $("#cambio-password").show();
    $(".reset-pass").val("");
    $("#pass-aviso").hide();
});

$("#generar_pass").on("click",function(){
    var cadena = "ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrtuvwxyz0123456789";
    var longitudCadena=cadena.length;
    var pass = "";
    var longitudPass=10;
    var pos=0;
    for(var i=1 ; i<=longitudPass ; i++){
        pos=Math.random()*longitudCadena;
        if(pos==58)pos=57;
        pass += cadena.substr(pos,1);
    }
  $("#password").val(pass);
});

$("#confirmar-cambiopass").on("click",function(){
    var a=document.getElementById("pass").value;
    //console.log(a);
    $.ajax({
    type: 'post',
    url: a,
    data: $("#pass-change").serialize(),
    success: function (data) {
      if(data!="Exito"){
        //console.log(data);
        $("#pass-aviso").show();
        $("#pass-aviso").empty();
        $("#pass-aviso").append(data);
      }
      else{
        $("#pass-aviso").hide();
        $("#cambio-password").hide();
        $("#overlay").show();
        $("#pass-exito").show();
        $("#pass-exito2").show();
      }
    }
  });    
});
$("#pass-aceptar").on("click",function(){
  $("#overlay").hide();
  $("#pass-exito").hide();
});

$("#cancelar-cambiopass").on("click",function(){
    $("#overlay").hide();
    $("#cambio-password").hide();
});
$("#form-user-data").validate({
    submitHandler: function() {
    $.ajax({
    type: 'post',
    url: '../models/usuario-update.php',
    data: $("#form-user-data").serialize(),
    success: function (data) {
      //console.log(data);
      $("#overlay").show();
      $("#aceptar-cambio").show();
      $("#mensaje").empty();
      $("#mensaje").append(data);
    }
  });
}
});
$("#form-pass-change").validate({
    submitHandler: function() {
    $.ajax({
    type: 'post',
    url: 'models/cambio-password.php',
    data: $("#form-pass-change").serialize(),
    success: function (data) {
      //console.log(data);
      $("#overlay").show();
      $("#aceptar-cambio").show();
      $("#mensaje").empty();
      $("#mensaje").append(data);
    }
  });
}
});

$('#nuevo-usuario').validate({
    submitHandler: function() {
  $.ajax({
    type: 'post',
    url: '../models/agregar-usuario.php',
    data: $("#nuevo-usuario").serialize(),
    success: function (data) {
      //console.log(data);
      $("#overlay").show();
      $("#aceptar-cambio").show();
      $("#mensaje").empty();
      $("#mensaje").append(data);
    }
  });
}
});
/*26-08-2020 DMQ*/
$("#boton-personal").on("click",function(){
    $(".datos-personal").css("display","inline-block");
    $(".datos-empleo").css("display","none");
    $("#boton-empleo").addClass("tab-enabled");
    $("#boton-empleo").removeClass("tab-disabled");
    $("#boton-personal").addClass("tab-disabled");
    $("#boton-personal").removeClass("tab-enabled");
});
$("#boton-empleo").on("click",function(){
    $(".datos-personal").css("display","none");
    $(".datos-empleo").css("display","inline-block");
    $("#boton-personal").addClass("tab-enabled");
    $("#boton-personal").removeClass("tab-disabled");
    $("#boton-empleo").addClass("tab-disabled");
    $("#boton-empleo").removeClass("tab-enabled");
});
/*Fin de actualización*/
$("#pais").on("change",function(){
  if($("input[name=ubicacion]:checked").val()=="E" || $("#residencia").val()=="E"){
    if($("#pais").val()=="MEX" || $("#pais").val()=="1")
      $("#pais").val("");
  }
  if($("input[name=ubicacion]:checked").val()=="M" || $("#residencia").val()=="M"){
    if($("#pais").val()!="MEX" && $("#pais").val()!="1")
      $("#pais").val("MEX");
  }
  tipo_ubicacion();
  //console.log($("input[name=ubicacion]:checked").val());
});
$("#pais2").on("change",function(){
  if($("input[name=ubicacion2]:checked").val()=="E"){
    if($("#pais2").val()=="MEX" || $("#pais2").val()=="1")
      $("#pais2").val("");
  }
  if($("input[name=ubicacion2]:checked").val()=="M"){
    if($("#pais2").val()!="MEX" && $("#pais2").val()!="1")
      $("#pais2").val("MEX");
  }
  //tipo_ubicacion();
  //console.log($("input[name=ubicacion]:checked").val());
});

$(".quitar-aviso").on("click",function(){
      $("#overlay").hide();
      $("#aceptar-cambio").hide();
});
$("#fecha_egreso").on("blur",function(){
  if($("#fecha_ingreso").val() >= $("#fecha_egreso").val() && $("#fecha_egreso").val()!=""){
    $("#fecha_egreso").val("");
    $("#aviso_fecha").show();
  }
  else{
    $("#aviso_fecha").hide();
  }
});
$("#fecha_ingreso").on("change",function(){
  if($("#fecha_ingreso").val() >= $("#fecha_egreso").val() && $("#fecha_egreso").val()!=""){
    $("#fecha_egreso").val("");
    $("#aviso_fecha").show();
  }
  else{
    $("#aviso_fecha").hide();
  }
});
  $('#plantilla-usuarios').on('submit', function(event){
    event.preventDefault();
    $.ajax({
      url:"../models/importar-usuarios.php",
      method:"POST",
      data:new FormData(this),
      contentType:false,
      cache:false,
      processData:false,
      beforeSend:function(){
        $('#guardar-user').attr('disabled', 'disabled');
        //$('#guardar-user').val('Cargando...');
      document.getElementById("guardar-user").innerHTML="Cargando...";
      },
      success:function(data)
      {
      //$("#overlay").show();
      console.log(data);
      document.getElementById("confirmacion").innerHTML=data;
        $('#guardar-user').attr('disabled', false);
        //$('#guardar-user').val('Cargar archivos');
      document.getElementById("guardar-user").innerHTML="Cargar archivo";
      }
    })
  });

$('#form-content').validate({
  //errorElement: "span",
    submitHandler: function() {
  var formData = new FormData(document.getElementById("form-content"));
  $.ajax({
    type: 'post',
    url: '../models/guardarforms.php',
    data: formData,
    processData: false,
    contentType: false,
    success: function (data) {
      //console.log(data);
      $("#overlay").show();
      $("#aceptar-cambio").show();
      $("#mensaje").empty();
      $("#mensaje").append(data);
    }
  });
}
});

$(".seccion-anterior").on("click",function(){
    $.ajax({
      type: 'post',
      url: '../models/guardarforms.php',
      data: $("#form-content").serialize(),
    });
});
$(".seccion-siguiente").on("click",function(){
    $.ajax({
      type: 'post',
      url: '../models/guardarforms.php',
      data: $("#form-content").serialize(),
    });
});

$(".seccion-anterior-i").on("click",function(){
    $.ajax({
      type: 'post',
      url: '../../models/guardarforms.php',
      data: $("#form-content-i").serialize(),
    });
});

$(".seccion-siguiente-i").on("click",function(){
    $.ajax({
      type: 'post',
      url: '../../models/guardarforms.php',
      data: $("#form-content-i").serialize(),
/*    success: function (data) {
      console.log(data);
      $("#overlay").show();
      $("#aceptar-cambio").show();
      $("#mensaje").empty();
      $("#mensaje").append(data);
    }
    */
    });

});

$("#finalizar").on("click",function(){
    var tipo_decl=document.getElementsByName("tipo-declaracion")[0].value;
    //console.log(tipo_decl);
    if(tipo_decl=="P"){
      var url="../models/guardarforms.php";
    }
    else{
      var url="../../models/guardarforms.php";
    }
    $.ajax({
      type: 'post',
      url: url,
      data: $("#comprobar").serialize(),
    success: function (data) {
      //console.log(data);
      $("#overlay").show();
      $("#envio_declaracion").val(data);
      //console.log(data);
      if(data=="N"){
        $("#aceptar-cambio2").hide();
        $("#mensaje2").empty();
        $("#finalizar-declaracion").show();      }
      else{
        $("#aceptar-cambio2").show();
        $("#mensaje2").empty();
        $("#mensaje2").append("Hay formularios pendientes.");
      }
    }
    });

});
$('#reporte').on('submit', function(event){
   event.preventDefault();
   var enlace="../views/reportes/" + document.getElementById("enlace").value + ".php";
   var excel="reportes/" + document.getElementById("enlace2").value + ".xlsx";
   $.ajax({
   url: enlace,
   method:"POST",
   data: $("#reporte").serialize(),
   beforeSend:function(){    
     $('#generar').attr('disabled', 'disabled');
     document.getElementById('generar').value = "Generando...";
    },
   success:function(data){
      $('#generar').attr('disabled', false);
      document.getElementById('generar').value = "Generar";
       if(data=="N"){
          $("#overlay").show();
          $("#aceptar-cambio").show();
          $("#mensaje").empty();
          $("#mensaje").append("No se encontraron resultados.");
       }
       else if(data=="F"){
          $("#overlay").show();
          $("#aceptar-cambio").show();
          $("#mensaje").empty();
          $("#mensaje").append("Debe agregar al menos un criterio de búsqueda.");
       }
       else{
          window.location=excel;
       }
       //console.log(data);
       //win.location.href = data;
       }
    });
  });

$("#cancelar-envio").on("click",function(){
      $("#overlay").hide();
      $("#finalizar-declaracion").hide();
});

$("#aceptar-envio").on("click",function(){
    $.ajax({
      type: 'post',
      url: '../models/guardarforms.php',
      data: $("#envio-dec").serialize(),
    });
});
$("#aceptar-envio-i").on("click",function(){
    $.ajax({
      type: 'post',
      url: '../../models/guardarforms.php',
      data: $("#envio-dec").serialize(),
    });
});


$('#form-content-i').validate({
  //errorElement: "span",
    submitHandler: function() {
  $.ajax({
    type: 'post',
    url: '../../models/guardarforms.php',
    data: $("#form-content-i").serialize(),
    success: function (data) {
      //console.log(data);
      $("#overlay").show();
      $("#aceptar-cambio").show();
      $("#mensaje").empty();
      $("#mensaje").append(data);
    }
  });
}
});

/*$("#guardari").on("click",function(){
  $.ajax({
    type: 'post',
    url: '../../models/guardarforms.php',
    data: $("#form-content").serialize(),
    success: function (data) {
      console.log(data);
      $("#overlay").show();
      $("#aceptar-cambio").show();
      $("#mensaje").empty();
      $("#mensaje").append(data);
    }
  });  
});
*/
$("#conformidad_s").on("click",function(){
  $("#conformidad").val("S");
  $.ajax({
    type: 'post',
    url: '../models/guardarforms.php',
    data: $("#form-conformidad").serialize(),
    success: function (data) {
      //console.log(data);
    }
  });
});
$("#conformidad_n").on("click",function(){
  $("#conformidad").val("N");
  $.ajax({
    type: 'post',
    url: '../models/guardarforms.php',
    data: $("#form-conformidad").serialize(),
    success: function (data) {
      //console.log(data);
    }
  });
});

$("#cp-list").on("keyup",function(){
  $.ajax({
    type: 'post',
    url: '../models/codigos-postales.php',
    data: $("#cp-list").serialize(),
    success: function (data) {
      //console.log(data);
    }
  });  

});

$("#buscar-direccion").on("click",function(){
  $.ajax({
    type: 'post',
    url: '../models/codigos-postales.php',
    data: $("#cp").serialize(),
    success: function (data) {
      //console.log(data);
        if(data==1 || !data){
          $("#msj-cp").empty();
          $("#msj-cp").append("El cp no existe.");
          $("#estado").val("");
          $("#municipio").val("");
          $("#colonia-select").val("");
          $("#colonia").val("");
          $("#colonia2-div").hide();
          $("#colonia-div").show();
        }
        else{
          var datos= JSON.parse(data);
          //console.log(datos.colonias);
          $("#msj-cp").empty();
          $("#estado").val(datos.estado);
          $("#municipio").empty();
          $("#municipio").append(datos.municipio);
          if(datos.colonias){
              //console.log("va");
            //$("#colonia-div").empty();
            $("#colonia-div").hide();
            $("#colonia").val("");
            $("#colonia2-div").show();
            $("#colonia-select").empty();
            $("#colonia-select").append(datos.colonias);
          }
          else{
            $("#colonia-select").val("");
            $("#colonia-div").show();
            $("#colonia2-div").hide();            
          }
        }
      
    }
  });

});

$("#estado").on("change",function(){
  estado();
});

$("#quitar-agregar").on("click",function(){
      $("#overlay").hide();
      $("#aviso-agregar").hide();  
});

$("#registro-mas").on("click",function(){
  var movimiento=document.getElementById("movimiento").value;
  //console.log(movimiento.length);
  if(movimiento.length){
    var total=document.getElementById("total_reg").value;
    var total2=document.getElementById("total_reg2").value;
    //console.log(movimiento+" "+total+" "+total2+" "+document.getElementById("total").innerHTML);
    if(total < document.getElementById("total").innerHTML){
      $("#overlay").show();
      $("#aviso-agregar").show();
      $("#msj").empty();
      $("#msj").append("No se puede agregar otro registro hasta guardar el actual.");
    }
    var nuevo=parseInt(total)+1;
    var nuevo2=parseInt(total2)+1;
    document.getElementById("total").innerHTML=nuevo;
    document.getElementById("variable").innerHTML=nuevo;
    $("#secuencia").val(nuevo2);
    $("#pendientes").text("");
    if(document.getElementById("form-content")){
      document.getElementById("form-content").reset();
      resetForm($('#form-content')); // by id, recommended
    }
    if(document.getElementById("form-content-i")){
      document.getElementById("form-content-i").reset();
      resetForm($('#form-content-i')); // by id, recommended
    }
    if(document.getElementById("sueldo_mensual"))
      document.getElementById("sueldo_mensual").value=0;
      cambios_dinamicos();
  }
  else{
      $("#overlay").show();
      $("#aviso-agregar").show();
      $("#msj").empty();
      $("#msj").append("No se puede agregar otro registro hasta guardar el actual.");
  }
});

$("#baja").on("click",function(){
  baja();
});

$("#ninguno").on("click",function(){
  $("#tipo_comodato").val("");
  $("#div-inmueble").hide();
  $("#div-vehiculo").hide();
  ninguno();
});

$("#puesto").on("change",function(){
  $.ajax({
    type: 'post',
    url: '../models/nivel-empleo.php',
    data: $("#puesto").serialize(),
    success: function (data) {
      if(data){
      //console.log(data);
      $("#nivel").val(data);
      }
    }
  });  
});
$("#puesto1").on("change",function(){
  $.ajax({
    type: 'post',
    url: '../models/nivel-empleo.php',
    data: $("#puesto1").serialize(),
    success: function (data) {
      if(data){
      //console.log(data);
      $("#nivel").val(data);
      }
    }
  });  
});

$(".aviso-declaracion").on("click",function(){
  $("#overlay").show();
  $("#aceptar-cambio").show();
});

//21-08-2020 DMQ-Qualsys Camnio de opciones de catálogo Puestos
$(".puesto-modificar").on("click",function(){
  $("#overlay").show();
  $("#nuevo-elemento").show();
  $("#mensaje").empty();
  var clave=document.getElementById("clave"+this.id).value;
  var nombre=document.getElementById("nombre"+this.id).value;
  var desc_corta=document.getElementById("desc_corta"+this.id).value;
  var nivel=document.getElementById("nivel"+this.id).value;
  var declaracion=document.getElementById("declaracion"+this.id).value;
  var fecha_efec=document.getElementById("fecha"+this.id).value;
  var estatus=document.getElementById("estatus"+this.id).value;
  document.getElementById("id_puesto").innerHTML=document.getElementById("id"+this.id).value
  $("#clave").val(clave);
  $("#descripcion").val(nombre);
  $("#descripcion_corta").val(desc_corta);
  $("#fecha_efectiva").val(fecha_efec);
  $("#nivel").val(nivel);
  document.getElementById("validar").value=document.getElementById("id"+this.id).value;
  var declara=document.getElementsByName("declara");
  for (var i = declara.length - 1; i >= 0; i--) {
    if(declaracion=="C"){
      if(declara[i].value == "C")declara[i].checked=true;
    }
    if(declaracion=="P"){
      if(declara[i].value == "P")declara[i].checked=true;
    }
    if(declaracion=="N"){
      if(declara[i].value == "N")declara[i].checked=true;
      }
    }
  var estado=document.getElementsByName("estatus");
  for (var i = estado.length - 1; i >= 0; i--) {
    if(estatus=="A"){
      if(estado[i].value == "A")estado[i].checked=true;
    }
    if(estatus=="I"){
      if(estado[i].value == "I")estado[i].checked=true;
      }
    }
});
//Fin de actualización

/* 20-08-2020 DMQ-Qualsys Llenado de formularios de áreas y dependencias */
$(".area-modificar").on("click",function(){
  $("#overlay").show();
  $("#nuevo-elemento").show();
  $("#mensaje").empty();
  var clave=document.getElementById("clave"+this.id).value;
  var nombre=document.getElementById("nombre"+this.id).value;
  var desc_corta=document.getElementById("desc_corta"+this.id).value;
  var estatus=document.getElementById("estatus"+this.id).value;
  $("#clave").val(clave);
  $("#descripcion").val(nombre);
  $("#descripcion_corta").val(desc_corta);
  document.getElementById("validar").value=document.getElementById("clave"+this.id).value;
  var estado=document.getElementsByName("estatus");
  for (var i = estado.length - 1; i >= 0; i--) {
    if(estatus=="A"){
      if(estado[i].value == "A")estado[i].checked=true;
    }
    if(estatus=="I"){
      if(estado[i].value == "I")estado[i].checked=true;
      }
    }
});

$(".depend-modificar").on("click",function(){
  $("#overlay").show();
  $("#nuevo-elemento").show();
  $("#mensaje").empty();
  var nombre=document.getElementById("nombre"+this.id).value;
  var desc_corta=document.getElementById("desc_corta"+this.id).value;
  var principal=document.getElementById("principal"+this.id).value;
  var estatus=document.getElementById("estatus"+this.id).value;
  $("#descripcion").val(nombre);
  $("#descripcion_corta").val(desc_corta);
  if(principal=="X"){
    $("#check-depend").prop("checked",true);
  }
  document.getElementById("validar").value=document.getElementById("clave"+this.id).value;
  var estado=document.getElementsByName("estatus");
  for (var i = estado.length - 1; i >= 0; i--) {
    if(estatus=="A"){
      if(estado[i].value == "A")estado[i].checked=true;
    }
    if(estatus=="I"){
      if(estado[i].value == "I")estado[i].checked=true;
      }
    }
});
/* 02-09-2020 DMQ-Qualsys Catálogos nuevos. */
$(".pais-modificar").on("click",function(){
  $("#overlay").show();
  $("#nuevo-elemento").show();
  $("#mensaje").empty();
  var clave=document.getElementById("clave"+this.id).value;
  var nombre=document.getElementById("nombre"+this.id).value;
  var estatus=document.getElementById("estatus"+this.id).value;
  $("#clave").val(clave);
  $("#descripcion").val(nombre);
  document.getElementById("validar").value=document.getElementById("clave"+this.id).value;
  var estado=document.getElementsByName("estatus");
  for (var i = estado.length - 1; i >= 0; i--) {
    if(estatus=="A"){
      if(estado[i].value == "A")estado[i].checked=true;
    }
    if(estatus=="I"){
      if(estado[i].value == "I")estado[i].checked=true;
      }
    }
});
$(".municipio-modificar").on("click",function(){
  $("#overlay").show();
  $("#nuevo-elemento").show();
  $("#mensaje").empty();
  var clave=document.getElementById("clave"+this.id).value;
  var nombre=document.getElementById("nombre"+this.id).value;
  var estatus=document.getElementById("estatus"+this.id).value;
  $("#clave").val(clave);
  $("#descripcion").val(nombre);
  console.log(document.getElementById("estatus"+this.id).value);
  document.getElementById("estado-mun").value=document.getElementById("estado"+this.id).value;
  document.getElementById("estado-mun-ant").value=document.getElementById("estado"+this.id).value;
  document.getElementById("validar").value=document.getElementById("clave"+this.id).value;
  var estado=document.getElementsByName("estatus");
  for (var i = estado.length - 1; i >= 0; i--) {
    if(estatus=="A"){
      if(estado[i].value == "A")estado[i].checked=true;
    }
    if(estatus=="I"){
      if(estado[i].value == "I")estado[i].checked=true;
      }
    }
});
$(".banco-modificar").on("click",function(){
  $("#overlay").show();
  $("#nuevo-elemento").show();
  $("#mensaje").empty();
  var clave=document.getElementById("clave"+this.id).value;
  var nombre=document.getElementById("nombre"+this.id).value;
  var nombre_largo=document.getElementById("nombre_largo"+this.id).value;
  var estatus=document.getElementById("estatus"+this.id).value;
  $("#clave").val(clave);
  $("#descripcion").val(nombre);
  $("#descripcion_larga").val(nombre_largo);
  document.getElementById("validar").value=document.getElementById("clave"+this.id).value;
  var estado=document.getElementsByName("estatus");
  for (var i = estado.length - 1; i >= 0; i--) {
    if(estatus=="A"){
      if(estado[i].value == "A")estado[i].checked=true;
    }
    if(estatus=="I"){
      if(estado[i].value == "I")estado[i].checked=true;
      }
    }
});

$("#estado-select").on("change",function(){
  //$("#municipio").val(this.val());
  //console.log(document.getElementById("estado-select").value);
//  document.getElementById("estado-mun").value=document.getElementById("estado-select").value;
});

$("#valor_adquisicion").on("keyup", function () {
  separador_punto(this);
});
$("#sup_terreno").on("keyup", function () {
  separador_sup(this);
});
$("#sup_construc").on("keyup", function () {
  separador_sup(this);
});
$("#sueldo_mensual").on("keyup", function () {
  separador(this);
});
$("#saldo").on("keyup", function () {
  separador(this);
});
$("#monto_original").on("keyup", function () {
  separador(this);
});
$("#monto_mensual").on("keyup", function () {
  separador(this);
});

$("#activ_industrial").on("keyup", function () {
  separador(this);
  sumaingresos();
  sumatotales();
  separador(document.getElementById("otros_ingresos"));
  separador(document.getElementById("ingreso_neto"));
  separador(document.getElementById("ingreso_total"));
});
$("#activ_financiera").on("keyup",function(){
  separador(this);
  sumaingresos();
  sumatotales();
  separador(document.getElementById("otros_ingresos"));
  separador(document.getElementById("ingreso_neto"));
  separador(document.getElementById("ingreso_total"));
});
$("#serv_profesionales").on("keyup",function(){
  separador(this);
  sumaingresos();
  sumatotales();
  separador(document.getElementById("otros_ingresos"));
  separador(document.getElementById("ingreso_neto"));
  separador(document.getElementById("ingreso_total"));
});
$("#enajena_bienes").on("keyup",function(){
  separador(this);
  sumaingresos();
  sumatotales();
  separador(document.getElementById("otros_ingresos"));
  separador(document.getElementById("ingreso_neto"));
  separador(document.getElementById("ingreso_total"));
});
$("#no_considerados").on("keyup",function(){
  separador(this);
  sumaingresos();
  sumatotales();
  separador(document.getElementById("otros_ingresos"));
  separador(document.getElementById("ingreso_neto"));
  separador(document.getElementById("ingreso_total"));
});

$("#remunera_neta").on("keyup",function(){
  separador(this);
  ingresoneto();
  sumatotales();
  separador(document.getElementById("ingreso_neto"));
  separador(document.getElementById("ingreso_total"));
});
$("#otros_ingresos").change(function(){
  separador(this);
  ingresoneto();
  sumatotales();
  separador(document.getElementById("ingreso_neto"));
  separador(document.getElementById("ingreso_total"));
});

$("#ingreso_neto").on("keyup",function(){
  separador(this);
  sumatotales();
  separador(document.getElementById("ingreso_total"));
});

$("#ingreso_pareja").on("keyup",function(){
  separador(this);
  sumatotales();
  separador(document.getElementById("ingreso_total"));
});

$("#como_participa").on("change",function(){
  como_participa();
});

$('#form-tiempos').validate({
  submitHandler: function() {
  $.ajax({
    type: 'post',
    url: '../models/tiempos-notificaciones.php',
    data: $("#form-tiempos").serialize(),
    success: function (data) {
      //console.log(data);
      $("#overlay").show();
      $("#aceptar-cambio").show();
      $("#mensaje").empty();
      $("#mensaje").append(data);
    }
  });  
}
});

$('#form-config').validate({
  //preventDefault();
  submitHandler: function() {
  var formData = new FormData(document.getElementById("form-config"));
  $.ajax({
    type: 'post',
    url: '../models/configuracion.php',
    data: formData,
    processData: false,
    contentType: false,
    success: function (data) {
      //console.log(data);
      $("#overlay").show();
      $("#aceptar-cambio").show();
      $("#mensaje").empty();
      $("#mensaje").append("Los datos han sido guardados.");
    }
  });  
}
});

$("#nuevo_elemento").on("click",function(){
  $("#overlay").show();
  $("#nuevo-elemento").show();
  $("#clave").val("");
  $("#descripcion").val("");
  $("#descripcion_corta").val("");
  $("#nivel").val("");
  $("#mensaje").empty();
  $("#validar").val("X");
});
$("#cancelar_elemento").on("click",function(){
  $("#overlay").hide();
  $("#nuevo-elemento").hide();
});
$("#otra-busqueda").on("click",function(){
  document.getElementById('lupa').click();
});
$('#form-nuevo-elemento').validate({
  submitHandler: function() {
  $.ajax({
    type: 'post',
    url: '../models/agregar-elemento.php',
    data: $("#form-nuevo-elemento").serialize(),
    success: function (data) {
      //console.log(data);
      if(data==""){
        $("#nuevo-elemento").hide();
        $("#aceptar-cambio").show();
      }
      else{
        $("#mensaje").empty();        
        $("#mensaje").append(data);
      }
    }
  });  
}
});
 $("#file").change(function (){
   var fileName = $(this).val();
   $("#archivo-cargado").show();
   $("#archivo-cargado").html("Imagen cargada");
 });
 $("#logo_nivel").change(function (){
   var fileName = $(this).val();
   $("#archivo-cargado1").show();
   $("#archivo-cargado1").html("Imagen cargada");
    var archivo = new FileReader();
    archivo.readAsDataURL(document.getElementById("logo_nivel").files[0]);

    archivo.onload = function (oFREvent) {
        document.getElementById("img_nivel").src = oFREvent.target.result;
    };
 });
 $("#logo_depend").change(function (){
   var fileName = $(this).val();
   $("#archivo-cargado2").show();
   $("#archivo-cargado2").html("Imagen cargada");
    var archivo = new FileReader();
    archivo.readAsDataURL(document.getElementById("logo_depend").files[0]);

    archivo.onload = function (oFREvent) {
        document.getElementById("img_depend").src = oFREvent.target.result;
    };
 });

$("#cambiar-foto").on("click",function(){
  document.getElementById('file').click();
});
$("#cambiar-logo").on("click",function(){
  document.getElementById('logo_nivel').click();
});
$("#cambiar-logo-d").on("click",function(){
  document.getElementById('logo_depend').click();
});
$("#check-declarante").on("click",function(){
  if($("#check-declarante").is(":checked")){
   $("#puesto1").prop('required', true);
   $("#area1").prop('required', true);
   $(".ast2").css("display","inline");
  }
  else{
   $("#puesto1").prop('required', false);
   $("#area1").prop('required', false);    
   $(".ast2").css("display","none");
  }

});
}
function ingresoneto(){
  var a= document.getElementById("remunera_neta").value.replace(/,/g,"");
  var b= document.getElementById("otros_ingresos").value.replace(/,/g,"");
  if(a=="")a=0;
  if(b=="")b=0;
  var c= parseInt(a) + parseInt(b);
  $("#ingreso_neto").val(c);  
}
function sumatotales(){
  var a= document.getElementById("ingreso_neto").value.replace(/,/g,"");
  var b= document.getElementById("ingreso_pareja").value.replace(/,/g,"");
  if(a=="")a=0;
  if(b=="")b=0;
  var c= parseInt(a) + parseInt(b);
  $("#ingreso_total").val(c);

}
function estado(){
    var estado=document.getElementById("estado").value;
  $.ajax({
    type: 'post',
    url: '../models/codigos-postales.php',
    data: {"estado":estado},
    success: function (data) {
      //console.log(data);
      $("#municipio").empty();
      $("#municipio").append(data);
     // $("#municipio").val();
    }
  });
}
function comodato(){
  $(".oculto").hide();
  switch($("#tipo_comodato").val()){
    case "I":
      $("#tipo_vehiculo").val("");
      $("#marca").val("");
      $("#modelo").val("");
      $("#anio").val("");
      $("#serie").val("");
      $("#01mexico").prop('checked', false);
      $("#01extranjero").prop('checked', false);
      $("#pais2").val("");
      $("#estado3").val("");
      $("#dueno").val("");
      $("#nombre_dueno").val("");
      $("#rfc_dueno").val("");
      $("#relacion").val("");
      $("#otra_relacion").val("");
      $("#div-inmueble").css("display","inline-block");
      $("#container-inmueble").show();
      break;
    case "V":
      $("#tipo_inmueble").val("");
      $("#dommex").prop('checked', false);
      $("#domext").prop('checked', false);
      $("#pais").val("");
      $("#cp").val("");
      $("#estado").val("");
      $("#estado2").val("");
      $("#municipio").val("");
      $("#colonia").val("");
      $("#colonia-select").val("");
      $("#calle").val("");
      $("#num_exterior").val("");
      $("#num_interior").val("");
      $("#div-vehiculo").css("display","inline-block");
      $("#container-vehiculo").show();
      break;
    default:
      $("#tipo_vehiculo").val("");      
      $("#tipo_inmueble").val("");      
      break;
  }
}
function como_participa(){
  switch($("#como_participa").val()){
    case 'A':
      $("#fideicomitente-div").show();
      $("#fiduciario-div").hide();
      $("#fiduciario-div :input").val("");
      $("#fideicomisario-div").hide();
      $("#fideicomisario-div :input").val("");
      break;
    case 'B':
      $("#fiduciario-div").show();
      $("#fideicomitente-div").hide();
      $("#fideicomitente-div :input").val("");
      $("#fideicomisario-div").hide();
      $("#fideicomisario-div :input").val("");
      break;
    case 'C':
      $("#fideicomisario-div").show();
      $("#fideicomitente-div").hide();
      $("#fideicomitente-div :input").val("");
      $("#fiduciario-div").hide();
      $("#fiduciario-div :input").val("");
      break;
    case 'D':
    default:
      $("#fideicomitente-div").hide();
      $("#fideicomitente-div :input").val("");
      $("#fiduciario-div").hide();
      $("#fiduciario-div :input").val("");
      $("#fideicomisario-div").hide();
      $("#fideicomisario-div :input").val("");
      break;
  }
}
function tipo_ubicacion(){
    if($("#pais").val()=="1" || $("#pais").val()=="MEX"){
    $("#buscar-direccion").show();
    $("#municipio-tr").show();
    $("#estado-div").show();
    $("#estado2-div").hide();
    $("#estado2").val("");
  }
  else{
    $("#buscar-direccion").hide();
    $("#municipio-tr").hide();
    $("#estado-div").hide();
    $("#estado2-div").show();
  }
}
function estado_civil(){
  //console.log($("#edo_civil").val());
  if($("#edo_civil").val()=="CA"){
    $("#div_regm").show();
  }
  else{
   $("#div_regm").hide(); 
    $(".otro").hide();
   $("#regimen_mat").val(""); 
   $("#otro_regimen").val(""); 
  }

}
/*25-08-2020 DMQ-Qualsys Cambio de valor por default de dependencia. */
function ente(){
  var valor=document.getElementById("ente").value;
  $.ajax({
    type: 'post',
    url: '../models/dependencia-principal.php',
    data: {"dependencia":valor},
    success: function (data) {
      //console.log(data);
      if(data=="1"){
        $("#area").show();
        $("#puesto").show();
        $("#area2").hide();
        $("#puesto2").hide();
        $("#area2").val("");
        $("#puesto2").val("");
        $("#nivel").prop("readonly", true);
        $("#nivel").addClass("no_editable");
      }
      else{
        $("#area").hide();
        $("#puesto").hide();
        $("#area2").show();
        $("#puesto2").show();
        $("#area").val("");
        $("#puesto").val("");
        $("#nivel").prop("readonly", false);
        $("#nivel").removeClass("no_editable");
      }
      if(data=="2"){
        $("#otra_dependencia").show();
      }
      else{
        $("#otra_dependencia").hide();
        $("#otro_ente").val("");      
      }
    }
  });
}
/* Fin de actualización */
function sector_laboral(){
    if($("#sector").val()=="V" || $("#sector").val()=="O"){
    $(".caso_ninguno").show();
    $(".sector-publico").hide();
    $(".sector-privado").css("display","inline-block");
    $("#area").hide();
    $("#puesto").hide();
    $("#area2").show();
    $("#puesto2").show();
    $("#otra_dependencia").hide();

    if($("#sector").val()=="O"){
      $("#otro-ambito").show();

    }
    else{
      $("#otro-ambito").hide();
    }
  }
  else{
    if($("#sector").val()=="N"){
      $(".caso_ninguno").hide();
    }
    else{
      $(".caso_ninguno").show();
      $(".sector-privado").hide();
      $("#otro-sector").hide();
      $("#otro-ambito").hide();
      $(".sector-publico").show();
    }
  }
}
function otro_sector(){
     if($("#sector-pert").val()=="OT"){
     $("#otro-sector").show();       
   }
   else{
     $("#otro-sector").hide();
     $("#otro_sector").val("");
   }
}
function otra_relacion(){
      if($("#relacion_depend").val()=="OT"){
      $("#otra-relacion").show();
    }
    else{
      $("#otra-relacion").hide();    
    }
}
function otro_inmueble(){
      if($("#tipo_inmueble").val()=="O"){
      $("#otro-ambito").show();
    }
    else{
      $("#otro-ambito").hide();    
      $("#otro_inmueble").val("");    
    }
}
function otro_vehiculo(){
    if($("#tipo_vehiculo").val()=="O"){
    $("#otro-ambito").show();
  }
  else{
    $("#otro-ambito").hide();
    $("#otro_vehiculo").val("");
  }
}
function otro_mueble(){
    if($("#tipo_mueble").val()=="OT"){
    $("#otro-ambito").show();
  }
  else{
    $("#otro-ambito").hide();
    $("#tipo_descr").val("");
  }
}
function ninguno(){
  if($("#ninguno").is(":checked")){
    $("#movimiento").val("N");
    $(".ningun_registro").hide();
    $("#container-inmueble").hide();
    $("#container-vehiculo").hide();
  }
  else if($("#baja").is(":checked")){
    $("#movimiento").val("B");
    $(".ningun_registro").show();
  }
  else{
    $("#movimiento").val("A");
    $(".ningun_registro").show();
  }
}
function baja(){
  if($("#baja").is(":checked")){
    $("#movimiento").val("B");
    $(".motivo_baja").show();
  }
  else if($("#ninguno").is(":checked")){
    $("#movimiento").val("N");
    $(".motivo_baja").hide();
  }
  else{
    $("#movimiento").val("A");
    $(".motivo_baja").hide();
  }
}
function causa_baja(){
  if($("#causa_baja").val()=="O"){
    $("#otro-motivo").show();
  }
  else{
    $("#otro-motivo").hide();    
  }
}

function otra_relacion2(){
      if($("#relacion").val()=="OT"){
      $("#otra-rel").show();
    }
    else{
      $("#otra-rel").hide();    
      $("#otra-rel :input").val("");    
    }
}
function regimen_matrimonial(){
    if($("#regimen_mat").val()=="O"){
    $(".otro").show();
  }
  else{
    $(".otro").hide();
  }
}
function tipo_instrumento(){
      if($("#instrumento").val()=="O"){
      $("#otro-ambito").show();
    }
    else{
      $("#otro-ambito").hide();    
    }
}
function tipo_inversion(){
    $(".inversiones").hide();
    //$(".inversion").val("");
    switch($("#tipo_inversion").val()){
      case 'B':
        $("#bancaria-div").css("display","inline-block");
        $("#fondo").val("");
        $("#org_privada").val("");
        $("#monedas").val("");
        $("#seguros").val("");
        $("#valor_bursatil").val("");
        $("#afores").val("");
        break;
      case 'F':
        $("#fondos-div").css("display","inline-block");
        $("#bancaria").val("");
        $("#org_privada").val("");
        $("#monedas").val("");
        $("#seguros").val("");
        $("#valor_bursatil").val("");
        $("#afores").val("");
        break;
      case 'O':
        $("#org-privada-div").css("display","inline-block");
        $("#bancaria").val("");
        $("#fondo").val("");
        $("#monedas").val("");
        $("#seguros").val("");
        $("#valor_bursatil").val("");
        $("#afores").val("");
        break;
      case 'M':
        $("#monedas-div").css("display","inline-block");
        $("#bancaria").val("");
        $("#fondo").val("");
        $("#org_privada").val("");
        $("#seguros").val("");
        $("#valor_bursatil").val("");
        $("#afores").val("");
        break;
      case 'S':
        $("#seguros-div").css("display","inline-block");
        $("#bancaria").val("");
        $("#fondo").val("");
        $("#org_privada").val("");
        $("#monedas").val("");
        $("#valor_bursatil").val("");
        $("#afores").val("");
        break;
      case 'V':
        $("#valor-bursatil-div").css("display","inline-block");
        $("#bancaria").val("");
        $("#fondo").val("");
        $("#org_privada").val("");
        $("#monedas").val("");
        $("#seguros").val("");
        $("#afores").val("");
        break;
      case 'A':
        $("#afores-div").css("display","inline-block");
        $("#bancaria").val("");
        $("#fondo").val("");
        $("#org_privada").val("");
        $("#monedas").val("");
        $("#seguros").val("");
        $("#valor_bursatil").val("");
        break;
    }
}
function tipo_adeudo(){
      if($("#tipo_adeudo").val()=="O"){
      $("#otro-ambito").show();
    }
    else{
      $("#otro-ambito").hide();
      $("#otro_adeudo").val("");
    }
    if($("#tipo_adeudo").val()=="R"){
      $("#num-cuenta").hide();
      $("#num_cta").val("");
    }
    else{
      $("#num-cuenta").show();
    }

}
function tipo_institucion(){
  if($("#tipo_institucion").val()=="O"){
    $("#div_institucion").show();
  }
  else
    $("#div_institucion").hide();
}
function otro_beneficiario(){
  if($("#beneficiario").val()=="OT")
    $("#div_beneficiario").show();
  else
    $("#div_beneficiario").hide();

}
function otro_apoyo(){
  if($("#tipo_apoyo").val()=="O")
    $("#div_apoyo").show();
  else
    $("#div_apoyo").hide();
}
function otro_beneficio(){
  if($("#tipo_beneficio").val()=="O")
    $("#div_beneficio").show();
  else
    $("#div_beneficio").hide();
}
function resetForm($form) {
    //$form.find('input:text, input:password, input:file, select, textarea').val('');
    $form.find('input:text, input:password, input:file, select, textarea').val('');
    $form.find('input:checkbox')
         .removeAttr('checked').removeAttr('selected');
}

function rol_update(rfc,e,r,c,t){
  var e=document.getElementById(e).checked;
  var r=document.getElementById(r).checked;
  var c=document.getElementById(c).checked;
  var t=document.getElementById(t).checked;

  //console.log(rfc+" "+e+" "+r+" "+c+" "+t);
  $.ajax({
    type: 'post',
    url: '../models/checkbox-roles.php',
    data: {'rfc':rfc,'e':e,'r':r,'c':c,'t':t},
    success: function (data) {
      //alert("Cambio realizado.");
      //console.log(data);
      $("#overlay").show();
      $("#aceptar-cambio").show();
      //$(".search-table").empty();
      //$(".search-table").append(data);
    }
  });
}

/*function fecha_limite(){
  var rfc=document.getElementById("rfc_fecha").value;
  var dec=document.getElementById("dec_fecha").value;
  var tipo_decl=document.getElementById("select-tipo_decl").value;
  var ejercicio=document.getElementById("select-ejercicio").value;
  if(dec=="P"){
    var url='../models/fecha-limite.php';
  }
  else{
    var url='../../models/fecha-limite.php';    
  }
  if(rfc!="" && dec!="" && ejercicio!=""){
    $.ajax({
      type: 'post',
      url: url,
      data: {"rfc":rfc,"dec":dec,"tipo_decl":tipo_decl,"ejercicio":ejercicio},
      success: function (data) {
        console.log(data);
        $("#fecha-limite").val(data);
      }
    });
  }
}*/

function registro_menos(arr){
  //algo-=1;
  var a=document.getElementById("variable").innerHTML;
  var b=document.getElementById("total").innerHTML;
  var c=document.getElementById("total_reg").value;
  //console.log(a+" "+b+" "+c);
  if(a>c){
    document.getElementById("total").innerHTML=c;
    cambio_pagina_ant(arr);
  }
  else{
      $("#overlay").show();
      $("#aviso-agregar").show();
      $("#msj").empty();
      $("#msj").append("No se puede borrar este registro.");
  }
}
function cambios_dinamicos(){
  if(document.getElementById("tipo_comodato"))
      comodato();
  if(document.getElementById("sector"))
      sector_laboral();
  if(document.getElementById("ente"))
      ente();
  if(document.getElementById("sector-pert"))
    otro_sector();
  if(document.getElementById("relacion_depend"))
    otra_relacion();
  if(document.getElementById("tipo_participacion"))
    tipo_participacion();
  if(document.getElementById("relacion"))
    otra_relacion2();
  if(document.getElementById("residencia"))
    residencia();
 if(document.getElementById("tipo_inmueble"))
    otro_inmueble();
 if(document.getElementById("tipo_vehiculo"))
    otro_vehiculo();
 if(document.getElementById("tipo_mueble"))
    otro_mueble();
 if(document.getElementById("baja"))
    baja();
 if(document.getElementById("causa_baja"))
    causa_baja();
 if(document.getElementById("ninguno"))
    ninguno();
 if(document.getElementById("tipo_inversion"))
    tipo_inversion();
 if(document.getElementById("tipo_adeudo"))
    tipo_adeudo();
 if(document.getElementById("tipo_institucion"))
    tipo_institucion();
 if(document.getElementById("beneficiario"))
    otro_beneficiario();
 if(document.getElementById("tipo_apoyo"))
    otro_apoyo();
 if(document.getElementById("tipo_beneficio"))
    otro_beneficio();
 if(document.getElementsByName("remuneracion")){
  var remuneracion=document.getElementsByName("remuneracion");
  for (var i = remuneracion.length - 1; i >= 0; i--) {
    if(remuneracion[i].checked==true){
      if(remuneracion[i].value == "S")remuneracion_s();
      if(remuneracion[i].value == "N")remuneracion_n();
      }
    }
  }
 if(document.getElementsByName("ubicacion")){
  var ubicacion=document.getElementsByName("ubicacion");
  for (var i = ubicacion.length - 1; i >= 0; i--) {
    if(ubicacion[i].checked==true){
      if(ubicacion[i].value == "M"){ubicacion_m();ubicacion_m1();}
      if(ubicacion[i].value == "E"){ubicacion_e();ubicacion_e1();}
      }
    }
  }
 if(document.getElementsByName("ubicacion2")){
  var ubicacion=document.getElementsByName("ubicacion2");
  for (var i = ubicacion.length - 1; i >= 0; i--) {
    if(ubicacion[i].checked==true){
      if(ubicacion[i].value == "M"){ubicacion_m1();}
      if(ubicacion[i].value == "E"){ubicacion_e1();}
      }
    }
  }
 if(document.getElementsByName("forma_recep")){
  var forma_recep=document.getElementsByName("forma_recep");
  for (var i = forma_recep.length - 1; i >= 0; i--) {
    if(forma_recep[i].checked==true){
      if(forma_recep[i].value == "M")recepcion_m();
      if(forma_recep[i].value == "E")recepcion_e();
      }
    }
  }
    
 if(document.getElementById("como_participa"))
    como_participa();

 $("#contador").html(document.getElementById("observaciones").value.length);

}
function cambio_pagina_ant(arr){
  //console.log(arr);
  var a=document.getElementById("variable").innerHTML;
  if(a>1){
    document.getElementById("variable").innerHTML=parseInt(a)-1;
    a--;
  }
  //console.log(arr.form);
  if(arr.form==3){form3_datos(arr,a);}
  if(arr.form==4){form4_datos(arr,a);}
  if(arr.form==5){form5_datos(arr,a);}
  if(arr.form==6){form6_datos(arr,a);}
  if(arr.form==7){form7_datos(arr,a);}
  if(arr.form==10){form10_datos(arr,a);}
  if(arr.form==11){form11_datos(arr,a);}
  if(arr.form==12){form12_datos(arr,a);}
  if(arr.form==13){form13_datos(arr,a);}
  if(arr.form==14){form14_datos(arr,a);}
  if(arr.form==15){form15_datos(arr,a);}
  if(arr.form==16){form16_datos(arr,a);}
  if(arr.form==17){form17_datos(arr,a);}
  if(arr.form==18){form18_datos(arr,a);}
  if(arr.form==19){form19_datos(arr,a);}
  if(arr.form==20){form20_datos(arr,a);}
  if(arr.form==21){form21_datos(arr,a);}
  if(arr.form==22){form22_datos(arr,a);}
  cambios_dinamicos();
  if(document.getElementById("pais")){
    document.getElementById("pais").value=arr.pais[a-1];
  }
}

function cambio_pagina_sig(arr){
  var a=document.getElementById("variable").innerHTML;
  var b=document.getElementById("total").innerHTML;
  var c=document.getElementById("total_reg").value;
  if(arr.movimiento.length){
  //console.log(arr);
  }
  if(a < b){
    //console.log(arr.institucion[a]);
    a = parseInt(a)+1;
    document.getElementById("variable").innerHTML=a;
    //console.log(a+" "+b+" "+c);
    if(a>c){
      $("#secuencia").val(a);
      if(document.getElementById("form-content")){
      $("#pendientes").text("");
      document.getElementById("form-content").reset();
      resetForm($('#form-content'));
      }
      if(document.getElementById("form-content-i")){
      $("#pendientes").text("");
      document.getElementById("form-content-i").reset();
      resetForm($('#form-content-i'));
      }
    }
    else{
      if(arr.form==3){form3_datos(arr,a);}
      if(arr.form==4){form4_datos(arr,a);}
      if(arr.form==5){form5_datos(arr,a);}
      if(arr.form==6){form6_datos(arr,a);}
      if(arr.form==7){form7_datos(arr,a);}
      if(arr.form==10){form10_datos(arr,a);}
      if(arr.form==11){form11_datos(arr,a);}
      if(arr.form==12){form12_datos(arr,a);}
      if(arr.form==13){form13_datos(arr,a);}
      if(arr.form==14){form14_datos(arr,a);}
      if(arr.form==15){form15_datos(arr,a);}
      if(arr.form==16){form16_datos(arr,a);}
      if(arr.form==17){form17_datos(arr,a);}
      if(arr.form==18){form18_datos(arr,a);}
      if(arr.form==19){form19_datos(arr,a);}
      if(arr.form==20){form20_datos(arr,a);}
      if(arr.form==21){form21_datos(arr,a);}
      if(arr.form==22){form22_datos(arr,a);}
    }
    cambios_dinamicos();
    if(document.getElementById("pais")){
      document.getElementById("pais").value=arr.pais[a-1];
    }
  }
}

function form3_datos(arr,a){
  document.getElementById("pendientes").innerHTML=arr.html[a-1];
  document.getElementById("secuencia").value=arr.secuencia[a-1];
  document.getElementById("movimiento").value=arr.movimiento[a-1];
  document.getElementById("nivel_escolar").value=arr.nivel_escolar[a-1];
  document.getElementById("institucion").value=arr.institucion[a-1];
  document.getElementById("carrera").value=arr.carrera[a-1];
  document.getElementById("estatus_estudio").value=arr.estatus_estudio[a-1];
  document.getElementById("doc_obtenido").value=arr.doc_obtenido[a-1];
  document.getElementById("fecha_doc").value=arr.fecha_doc[a-1];
  document.getElementById("observaciones").value=arr.observaciones[a-1];
  var ubicacion=document.getElementsByName("ubicacion");
  for (var i = ubicacion.length - 1; i >= 0; i--) {
    if(ubicacion[i].value==arr.ubicacion[a-1]){
      ubicacion[i].checked="checked";
    }
  }
  if(arr.movimiento[a-1]=='B'){
    $("#baja").prop("checked",true);
  }
  else{
    $("#baja").prop("checked",false);
  }
}

function form4_datos(arr,a){
  document.getElementById("pendientes").innerHTML=arr.html[a-1];
  document.getElementById("secuencia").value=arr.secuencia[a-1];
  document.getElementById("movimiento").value=arr.movimiento[a-1];
  document.getElementById("nivel_gobierno").value=arr.orden_id[a-1];
  document.getElementById("ambito_publico").value=arr.ambito_id[a-1];
  document.getElementById("ente").value=arr.dependencia[a-1];
  document.getElementById("otro_ente").value=arr.dependencia_descr[a-1];
  document.getElementById("area").value=arr.area_adscripcion[a-1];
  document.getElementById("area2").value=arr.area_descr[a-1];
  document.getElementById("puesto").value=arr.puesto[a-1];
  document.getElementById("puesto2").value=arr.puesto_descr[a-1];
  document.getElementById("nivel").value=arr.nivel_descr[a-1];
  document.getElementById("fecha_empleo").value=arr.fecha_inicio[a-1];
  document.getElementById("funcion").value=arr.funcion_principal[a-1];
  document.getElementById("tel_oficina").value=arr.tel_oficina[a-1];
  document.getElementById("extension").value=arr.extension[a-1];
  document.getElementById("pais").value=arr.pais[a-1];
  document.getElementById("cp").value=arr.cp[a-1];
  document.getElementById("estado").value=arr.estado[a-1];
  document.getElementById("estado2").value=arr.estado_desc[a-1];
  document.getElementById("municipio").value=arr.municipio[a-1];
  lista_municipios(arr.municipio[a-1]);
  document.getElementById("colonia").value=arr.colonia_desc[a-1];
  document.getElementById("calle").value=arr.calle[a-1];
  document.getElementById("num_exterior").value=arr.num_exterior[a-1];
  document.getElementById("num_interior").value=arr.num_interior[a-1];
  document.getElementById("observaciones").value=arr.observaciones[a-1];
  var ubicacion=document.getElementsByName("ubicacion");
  for (var i = ubicacion.length - 1; i >= 0; i--) {
    if(ubicacion[i].value==arr.ubicacion[a-1]){
      ubicacion[i].checked="checked";
      if(ubicacion[i].value == "M")ubicacion_m();
      if(ubicacion[i].value == "E")ubicacion_e();
    }
  }
  var honorarios=document.getElementsByName("honorarios");
  for (var i = honorarios.length - 1; i >= 0; i--) {
    if(honorarios[i].value==arr.honorarios[a-1]){
      honorarios[i].checked="checked";
    }
  }
  if(arr.movimiento[a-1]=='B'){
    $("#baja").prop("checked",true);
  }
  else{
    $("#baja").prop("checked",false);
  }
}
function form5_datos(arr,a){
  document.getElementById("pendientes").innerHTML=arr.html[a-1];
  document.getElementById("secuencia").value=arr.secuencia[a-1];
  document.getElementById("movimiento").value=arr.movimiento[a-1];
  document.getElementById("sector").value=arr.actividad_laboral[a-1];
  document.getElementById("otro_ambito").value=arr.otra_actividad[a-1];
  document.getElementById("nivel_gobierno").value=arr.orden_id[a-1];
  document.getElementById("ambito_publico").value=arr.ambito_id[a-1];
  document.getElementById("ente").value=arr.dependencia[a-1];
  document.getElementById("otro_ente").value=arr.dependencia_descr[a-1];
  document.getElementById("area").value=arr.area_adscripcion[a-1];
  document.getElementById("puesto").value=arr.puesto[a-1];
  document.getElementById("ente2").value=arr.nombre_empresa[a-1];
  document.getElementById("area2").value=arr.area_descr[a-1];
  document.getElementById("puesto2").value=arr.puesto_descr[a-1];
  document.getElementById("rfc_empresa").value=arr.rfc_empresa[a-1];
  document.getElementById("funcion_principal").value=arr.funcion_principal[a-1];
  document.getElementById("fecha_ingreso").value=arr.fecha_inicio[a-1];
  document.getElementById("fecha_egreso").value=arr.fecha_fin[a-1];
  document.getElementById("sector-pert").value=arr.sector[a-1];
  document.getElementById("otro_sector").value=arr.otro_sector[a-1];
  document.getElementById("observaciones").value=arr.observaciones[a-1];
/*  document.getElementById("dependencia").value=arr.dependencia_descr[a-1];*/
  var ubicacion=document.getElementsByName("ubicacion");
  for (var i = ubicacion.length - 1; i >= 0; i--) {
    if(ubicacion[i].value==arr.ubicacion[a-1]){
      ubicacion[i].checked="checked";
    }
  }
  if(arr.movimiento[a-1]=='B'){
    $("#baja").prop("checked",true);
  }
  else{
    $("#baja").prop("checked",false);
  }
}
function form6_datos(arr,a){
  document.getElementById("pendientes").innerHTML=arr.html[a-1];
  document.getElementById("secuencia").value=arr.secuencia[a-1];
  document.getElementById("movimiento").value=arr.movimiento[a-1];
  document.getElementById("nombre").value=arr.nombre[a-1];
  document.getElementById("primer_apellido").value=arr.primer_apellido[a-1];
  document.getElementById("segundo_apellido").value=arr.segundo_apellido[a-1];
  document.getElementById("fecha_nac").value=arr.fecha_nac[a-1];
  document.getElementById("rfc_pareja").value=arr.rfc_pareja[a-1];
  document.getElementById("relacion_pareja").value=arr.relacion_pareja[a-1];
  document.getElementById("curp").value=arr.curp[a-1];
  document.getElementById("residencia").value=arr.residencia[a-1];
  document.getElementById("pais").value=arr.pais[a-1];
  document.getElementById("cp").value=arr.cp[a-1];
  document.getElementById("estado").value=arr.estado[a-1];
  document.getElementById("estado2").value=arr.estado_descr[a-1];
  lista_municipios(arr.municipio[a-1]);
  document.getElementById("colonia").value=arr.colonia_descr[a-1];
  document.getElementById("calle").value=arr.calle[a-1];
  document.getElementById("num_exterior").value=arr.num_exterior[a-1];
  document.getElementById("num_interior").value=arr.num_interior[a-1];
  document.getElementById("sector").value=arr.actividad_laboral[a-1];
  document.getElementById("otro_ambito").value=arr.otro_ambito[a-1];
  document.getElementById("nivel_gobierno").value=arr.orden_id[a-1];
  document.getElementById("ambito_publico").value=arr.ambito_id[a-1];
  document.getElementById("ente").value=arr.dependencia[a-1];
  document.getElementById("otro_ente").value=arr.dependencia_descr[a-1];
  document.getElementById("area").value=arr.area_adscripcion[a-1];
  document.getElementById("puesto").value=arr.puesto[a-1];
  document.getElementById("ente2").value=arr.nombre_empresa[a-1];
  document.getElementById("area2").value=arr.area_descr[a-1];
  document.getElementById("puesto2").value=arr.puesto_descr[a-1];
  document.getElementById("sector-pert").value=arr.sector[a-1];
  document.getElementById("otro_sector").value=arr.otro_sector[a-1];
  document.getElementById("rfc_empresa").value=arr.rfc_empresa[a-1];
  document.getElementById("funcion_principal").value=arr.funcion_principal[a-1];
  document.getElementById("fecha_ingreso").value=arr.fecha_inicio[a-1];
  document.getElementById("sueldo_mensual").value=arr.sueldo_mensual[a-1];
  document.getElementById("observaciones").value=arr.observaciones[a-1];
  separador(document.getElementById("sueldo_mensual"));

  var extranjero=document.getElementsByName("extranjero");
  for (var i = extranjero.length - 1; i >= 0; i--) {
    if(extranjero[i].value==arr.extranjero[a-1]){
      extranjero[i].checked="checked";
    }
  }

  var dependiente=document.getElementsByName("dependiente");
  for (var i = dependiente.length - 1; i >= 0; i--) {
    if(dependiente[i].value==arr.dependiente[a-1]){
      dependiente[i].checked="checked";
    }
  }

  var mismo_domicilio=document.getElementsByName("mismo_domicilio");
  for (var i = mismo_domicilio.length - 1; i >= 0; i--) {
    if(mismo_domicilio[i].value==arr.mismo_domicilio[a-1]){
      mismo_domicilio[i].checked="checked";
    }
  }

  var proveedor=document.getElementsByName("proveedor");
  for (var i = proveedor.length - 1; i >= 0; i--) {
    if(proveedor[i].value==arr.proveedor[a-1]){
      proveedor[i].checked="checked";
    }
  }
  if(arr.movimiento[a-1]=='B'){
    $("#baja").prop("checked",true);
  }
  else{
    $("#baja").prop("checked",false);
  }
  if(arr.movimiento[a-1]=='N'){
    $("#ninguno").prop("checked",true);
  }
  else{
    $("#ninguno").prop("checked",false);
  }

}

function form7_datos(arr,a){
  document.getElementById("pendientes").innerHTML=arr.html[a-1];
  document.getElementById("secuencia").value=arr.secuencia[a-1];
  document.getElementById("movimiento").value=arr.movimiento[a-1];
  document.getElementById("nombre").value=arr.nombre[a-1];
  document.getElementById("primer_apellido").value=arr.primer_apellido[a-1];
  document.getElementById("segundo_apellido").value=arr.segundo_apellido[a-1];
  document.getElementById("fecha_nac").value=arr.fecha_nac[a-1];
  document.getElementById("rfc_dependiente").value=arr.rfc_dependiente[a-1];
  document.getElementById("relacion_depend").value=arr.relacion_depend[a-1];
  document.getElementById("otra_relacion").value=arr.otra_relacion[a-1];
  document.getElementById("curp").value=arr.curp[a-1];
  document.getElementById("residencia").value=arr.residencia[a-1];
  document.getElementById("pais").value=arr.pais[a-1];
  document.getElementById("cp").value=arr.cp[a-1];
  document.getElementById("estado").value=arr.estado[a-1];
  document.getElementById("estado2").value=arr.estado_descr[a-1];
  lista_municipios(arr.municipio[a-1]);
  document.getElementById("colonia").value=arr.colonia_descr[a-1];
  document.getElementById("calle").value=arr.calle[a-1];
  document.getElementById("num_exterior").value=arr.num_exterior[a-1];
  document.getElementById("num_interior").value=arr.num_interior[a-1];
  document.getElementById("sector").value=arr.actividad_laboral[a-1];
  document.getElementById("otro_ambito").value=arr.otro_ambito[a-1];
  document.getElementById("nivel_gobierno").value=arr.orden_id[a-1];
  document.getElementById("ambito_publico").value=arr.ambito_id[a-1];
  document.getElementById("ente").value=arr.dependencia[a-1];
  document.getElementById("otro_ente").value=arr.dependencia_descr[a-1];
  document.getElementById("area").value=arr.area_adscripcion[a-1];
  document.getElementById("puesto").value=arr.puesto_id[a-1];
  document.getElementById("ente2").value=arr.nombre_empresa[a-1];
  document.getElementById("area2").value=arr.area_descr[a-1];
  document.getElementById("puesto2").value=arr.puesto_descr[a-1];
  document.getElementById("sector-pert").value=arr.sector[a-1];
  document.getElementById("otro_sector").value=arr.otro_sector[a-1];
  document.getElementById("rfc_empresa").value=arr.rfc_empresa[a-1];
  document.getElementById("funcion_principal").value=arr.funcion_principal[a-1];
  document.getElementById("fecha_ingreso").value=arr.fecha_inicio[a-1];
  document.getElementById("sueldo_mensual").value=arr.sueldo_mensual[a-1];
  document.getElementById("observaciones").value=arr.observaciones[a-1];
  separador(document.getElementById("sueldo_mensual"));

  var extranjero=document.getElementsByName("extranjero");
  for (var i = extranjero.length - 1; i >= 0; i--) {
    if(extranjero[i].value==arr.extranjero[a-1]){
      extranjero[i].checked="checked";
    }
  }

  var mismo_domicilio=document.getElementsByName("mismo_domicilio");
  for (var i = mismo_domicilio.length - 1; i >= 0; i--) {
    if(mismo_domicilio[i].value==arr.mismo_domicilio[a-1]){
      mismo_domicilio[i].checked="checked";
    }
  }

  var proveedor=document.getElementsByName("proveedor");
  for (var i = proveedor.length - 1; i >= 0; i--) {
    if(proveedor[i].value==arr.proveedor[a-1]){
      proveedor[i].checked="checked";
    }
  }

  if(arr.movimiento[a-1]=='B'){
    $("#baja").prop("checked",true);
  }
  else{
    $("#baja").prop("checked",false);
  }
  if(arr.movimiento[a-1]=='N'){
    $("#ninguno").prop("checked",true);
  }
  else{
    $("#ninguno").prop("checked",false);
  }
}

function form10_datos(arr,a){
  document.getElementById("pendientes").innerHTML=arr.html[a-1];
  document.getElementById("secuencia").value=arr.secuencia[a-1];
  document.getElementById("movimiento").value=arr.movimiento[a-1];
  document.getElementById("tipo_inmueble").value=arr.tipo_inmueble[a-1];
  document.getElementById("otro_inmueble").value=arr.otro_inmueble[a-1];
  document.getElementById("titular").value=arr.titular[a-1];
  document.getElementById("pct_propiedad").value=arr.pct_propiedad[a-1];
  document.getElementById("sup_terreno").value=arr.sup_terreno[a-1];
  document.getElementById("sup_construc").value=arr.sup_construc[a-1];
  document.getElementById("adquisicion").value=arr.adquisicion[a-1];
  document.getElementById("forma_pago").value=arr.forma_pago[a-1];
  document.getElementById("tercero").value=arr.tercero[a-1];
  document.getElementById("nombre_tercero").value=arr.nombre_tercero[a-1];
  document.getElementById("rfc_tercero").value=arr.rfc_tercero[a-1];
  document.getElementById("transmisor").value=arr.transmisor[a-1];
  document.getElementById("nombre_transmisor").value=arr.nombre_transmisor[a-1];
  document.getElementById("rfc_transmisor").value=arr.rfc_transmisor[a-1];
  document.getElementById("relacion").value=arr.relacion[a-1];
  document.getElementById("otra_relacion").value=arr.otra_relacion[a-1];
  document.getElementById("valor_adquisicion").value=arr.valor_adquisicion[a-1];
  document.getElementById("tipo_moneda").value=arr.tipo_moneda[a-1];
  document.getElementById("fecha_adquisicion").value=arr.fecha_adquisicion[a-1];
  document.getElementById("registro_publico").value=arr.registro_publico[a-1];
  document.getElementById("valor_conforme_a").value=arr.valor_conforme_a[a-1];
  document.getElementById("pais").value=arr.pais[a-1];
  document.getElementById("cp").value=arr.cp[a-1];
  document.getElementById("estado").value=arr.estado[a-1];
  document.getElementById("estado2").value=arr.estado_descr[a-1];
  lista_municipios(arr.municipio[a-1]);
  document.getElementById("colonia").value=arr.colonia_descr[a-1];
  document.getElementById("calle").value=arr.calle[a-1];
  document.getElementById("num_exterior").value=arr.num_exterior[a-1];
  document.getElementById("num_interior").value=arr.num_interior[a-1];
  document.getElementById("causa_baja").value=arr.causa_baja[a-1];
  document.getElementById("otra_causa").value=arr.otra_causa[a-1];
  document.getElementById("observaciones").value=arr.observaciones[a-1];
  separador_punto(document.getElementById("valor_adquisicion"));
  separador_sup(document.getElementById("sup_terreno"));
  separador_sup(document.getElementById("sup_construc"));

  var ubicacion=document.getElementsByName("ubicacion");
  for (var i = ubicacion.length - 1; i >= 0; i--) {
    if(ubicacion[i].value==arr.ubicacion[a-1]){
      ubicacion[i].checked="checked";
    }
  }

  if(arr.movimiento[a-1]=='B'){
    $("#baja").prop("checked",true);
    $(".motivo_baja").show();
  }
  else{
    $("#baja").prop("checked",false);
    $(".motivo_baja").hide();
  }
  if(arr.movimiento[a-1]=='N'){
    $("#ninguno").prop("checked",true);
  }
  else{
    $("#ninguno").prop("checked",false);
  }
}

function form11_datos(arr,a){
  document.getElementById("pendientes").innerHTML=arr.html[a-1];
  document.getElementById("secuencia").value=arr.secuencia[a-1];
  document.getElementById("movimiento").value=arr.movimiento[a-1];
  document.getElementById("tipo_vehiculo").value=arr.tipo_vehiculo[a-1];
  document.getElementById("otro_vehiculo").value=arr.otro_vehiculo[a-1];
  document.getElementById("titular").value=arr.titular[a-1];
  document.getElementById("tercero").value=arr.tercero[a-1];
  document.getElementById("nombre_tercero").value=arr.nombre_tercero[a-1];
  document.getElementById("rfc_tercero").value=arr.rfc_tercero[a-1];
  document.getElementById("transmisor").value=arr.transmisor[a-1];
  document.getElementById("nombre_transmisor").value=arr.nombre_transmisor[a-1];
  document.getElementById("rfc_transmisor").value=arr.rfc_transmisor[a-1];
  document.getElementById("relacion").value=arr.relacion[a-1];
  document.getElementById("otra_relacion").value=arr.otra_relacion[a-1];
  document.getElementById("marca").value=arr.marca[a-1];
  document.getElementById("modelo").value=arr.modelo[a-1];
  document.getElementById("anio").value=arr.anio[a-1];
  document.getElementById("serie").value=arr.serie[a-1];
  document.getElementById("estado").value=arr.estado[a-1];
  document.getElementById("pais").value=arr.pais[a-1];
  document.getElementById("adquisicion").value=arr.adquisicion[a-1];
  document.getElementById("forma_pago").value=arr.forma_pago[a-1];
  document.getElementById("valor_adquisicion").value=arr.valor_adquisicion[a-1];
  document.getElementById("tipo_moneda").value=arr.tipo_moneda[a-1];
  document.getElementById("fecha_adquisicion").value=arr.fecha_adquisicion[a-1];
  document.getElementById("causa_baja").value=arr.causa_baja[a-1];
  document.getElementById("otra_causa").value=arr.otra_causa[a-1];
  document.getElementById("observaciones").value=arr.observaciones[a-1];
  separador_punto(document.getElementById("valor_adquisicion"));

  var ubicacion=document.getElementsByName("ubicacion");
  for (var i = ubicacion.length - 1; i >= 0; i--) {
    if(ubicacion[i].value==arr.ubicacion[a-1]){
      ubicacion[i].checked="checked";
      if(ubicacion[i].value == "M")ubicacion_m1();
      if(ubicacion[i].value == "E")ubicacion_e1();
    }
  }

  if(arr.movimiento[a-1]=='B'){
    $("#baja").prop("checked",true);
    $(".motivo_baja").show();
  }
  else{
    $("#baja").prop("checked",false);
    $(".motivo_baja").hide();
  }
  if(arr.movimiento[a-1]=='N'){
    $("#ninguno").prop("checked",true);
  }
  else{
    $("#ninguno").prop("checked",false);
  }
}
function form12_datos(arr,a){
  document.getElementById("pendientes").innerHTML=arr.html[a-1];
  document.getElementById("secuencia").value=arr.secuencia[a-1];
  document.getElementById("movimiento").value=arr.movimiento[a-1];
  document.getElementById("tipo_mueble").value=arr.tipo_mueble[a-1];
  document.getElementById("tipo_descr").value=arr.tipo_descr[a-1];
  document.getElementById("titular").value=arr.titular[a-1];
  document.getElementById("tercero").value=arr.tercero[a-1];
  document.getElementById("nombre_tercero").value=arr.nombre_tercero[a-1];
  document.getElementById("rfc_tercero").value=arr.rfc_tercero[a-1];
  document.getElementById("transmisor").value=arr.transmisor[a-1];
  document.getElementById("nombre_transmisor").value=arr.nombre_transmisor[a-1];
  document.getElementById("rfc_transmisor").value=arr.rfc_transmisor[a-1];
  document.getElementById("relacion").value=arr.relacion[a-1];
  document.getElementById("otra_relacion").value=arr.otra_relacion[a-1];
  document.getElementById("descripcion").value=arr.descripcion[a-1];
  document.getElementById("adquisicion").value=arr.adquisicion[a-1];
  document.getElementById("forma_pago").value=arr.forma_pago[a-1];
  document.getElementById("valor_adquisicion").value=arr.valor_adquisicion[a-1];
  document.getElementById("tipo_moneda").value=arr.tipo_moneda[a-1];
  document.getElementById("fecha_adquisicion").value=arr.fecha_adquisicion[a-1];
  document.getElementById("causa_baja").value=arr.causa_baja[a-1];
  document.getElementById("otra_causa").value=arr.otra_causa[a-1];
  document.getElementById("observaciones").value=arr.observaciones[a-1];
  separador_punto(document.getElementById("valor_adquisicion"));

  if(arr.movimiento[a-1]=='B'){
    $("#baja").prop("checked",true);
    $(".motivo_baja").show();
  }
  else{
    $("#baja").prop("checked",false);
    $(".motivo_baja").hide();
  }
  if(arr.movimiento[a-1]=='N'){
    $("#ninguno").prop("checked",true);
  }
  else{
    $("#ninguno").prop("checked",false);
  }
}

function form13_datos(arr,a){
  document.getElementById("pendientes").innerHTML=arr.html[a-1];
  document.getElementById("secuencia").value=arr.secuencia[a-1];
  document.getElementById("movimiento").value=arr.movimiento[a-1];
  document.getElementById("tipo_inversion").value=arr.tipo_inversion[a-1];
  document.getElementById("bancaria").value=arr.bancaria[a-1];
  document.getElementById("fondo").value=arr.fondo[a-1];
  document.getElementById("org_privada").value=arr.org_privada[a-1];
  document.getElementById("monedas").value=arr.monedas[a-1];
  document.getElementById("seguros").value=arr.seguros[a-1];
  document.getElementById("valor_bursatil").value=arr.valor_bursatil[a-1];
  document.getElementById("afores").value=arr.afores[a-1];
  document.getElementById("titular").value=arr.titular[a-1];
  document.getElementById("tercero").value=arr.tercero[a-1];
  document.getElementById("nombre_tercero").value=arr.nombre_tercero[a-1];
  document.getElementById("rfc_tercero").value=arr.rfc_tercero[a-1];
  document.getElementById("num_cta").value=arr.num_cta[a-1];
  document.getElementById("razon_social").value=arr.razon_social[a-1];
  document.getElementById("rfc_institucion").value=arr.rfc_institucion[a-1];
  document.getElementById("pais").value=arr.pais[a-1];
  document.getElementById("saldo").value=arr.saldo[a-1];
  document.getElementById("tipo_moneda").value=arr.tipo_moneda[a-1];
  document.getElementById("observaciones").value=arr.observaciones[a-1];
  separador(document.getElementById("saldo"));

  var ubicacion=document.getElementsByName("ubicacion");
  for (var i = ubicacion.length - 1; i >= 0; i--) {
    if(ubicacion[i].value==arr.ubicacion[a-1]){
      ubicacion[i].checked="checked";
      if(ubicacion[i].value == "M")ubicacion_m();
      if(ubicacion[i].value == "E")ubicacion_e();
    }
  }

  if(arr.movimiento[a-1]=='B'){
    $("#baja").prop("checked",true);
  }
  else{
    $("#baja").prop("checked",false);
  }
  if(arr.movimiento[a-1]=='N'){
    $("#ninguno").prop("checked",true);
  }
  else{
    $("#ninguno").prop("checked",false);
  }
}
function form14_datos(arr,a){
  document.getElementById("pendientes").innerHTML=arr.html[a-1];
  document.getElementById("secuencia").value=arr.secuencia[a-1];
  document.getElementById("movimiento").value=arr.movimiento[a-1];
  document.getElementById("tipo_adeudo").value=arr.tipo_adeudo[a-1];
  document.getElementById("otro_adeudo").value=arr.otro_adeudo[a-1];
  document.getElementById("titular").value=arr.titular[a-1];
  document.getElementById("tercero").value=arr.tercero[a-1];
  document.getElementById("nombre_tercero").value=arr.nombre_tercero[a-1];
  document.getElementById("rfc_tercero").value=arr.rfc_tercero[a-1];
  document.getElementById("num_cta").value=arr.num_cta[a-1];
  document.getElementById("fecha_adquisicion").value=arr.fecha_adquisicion[a-1];
  document.getElementById("monto_original").value=arr.monto_original[a-1];
  document.getElementById("tipo_moneda").value=arr.tipo_moneda[a-1];
  document.getElementById("saldo").value=arr.saldo[a-1];
  document.getElementById("otorgante").value=arr.otorgante[a-1];
  document.getElementById("razon_social").value=arr.razon_social[a-1];
  document.getElementById("rfc_institucion").value=arr.rfc_institucion[a-1];
  document.getElementById("pais").value=arr.pais[a-1];
  document.getElementById("observaciones").value=arr.observaciones[a-1];
  separador(document.getElementById("saldo"));
  separador(document.getElementById("monto_original"));

  var ubicacion=document.getElementsByName("ubicacion");
  for (var i = ubicacion.length - 1; i >= 0; i--) {
    if(ubicacion[i].value==arr.ubicacion[a-1]){
      ubicacion[i].checked="checked";
      if(ubicacion[i].value == "M")ubicacion_m();
      if(ubicacion[i].value == "E")ubicacion_e();
    }
  }

  if(arr.movimiento[a-1]=='B'){
    $("#baja").prop("checked",true);
  }
  else{
    $("#baja").prop("checked",false);
  }
  if(arr.movimiento[a-1]=='N'){
    $("#ninguno").prop("checked",true);
  }
  else{
    $("#ninguno").prop("checked",false);
  }
}

function form15_datos(arr,a){
  document.getElementById("pendientes").innerHTML=arr.html[a-1];
  document.getElementById("secuencia").value=arr.secuencia[a-1];
  document.getElementById("movimiento").value=arr.movimiento[a-1];
  document.getElementById("tipo_comodato").value=arr.tipo_comodato[a-1];
  document.getElementById("tipo_inmueble").value=arr.tipo_inmueble[a-1];
  if(arr.otro_inmueble[a-1]=="")
    document.getElementById("otro").value=arr.otro_vehiculo[a-1];
  else
    document.getElementById("otro").value=arr.otro_inmueble[a-1];
  document.getElementById("pais").value=arr.pais[a-1];
  document.getElementById("cp").value=arr.cp[a-1];
  document.getElementById("estado").value=arr.estado[a-1];
  document.getElementById("estado2").value=arr.estado_descr[a-1];
  lista_municipios(arr.municipio[a-1]);
  document.getElementById("municipio").value=arr.municipio_descr[a-1];
  document.getElementById("colonia").value=arr.colonia_descr[a-1];
  document.getElementById("calle").value=arr.calle[a-1];
  document.getElementById("num_exterior").value=arr.num_exterior[a-1];
  document.getElementById("num_interior").value=arr.num_interior[a-1];
  document.getElementById("tipo_vehiculo").value=arr.tipo_vehiculo[a-1];
  document.getElementById("marca").value=arr.marca[a-1];
  document.getElementById("modelo").value=arr.modelo[a-1];
  document.getElementById("anio").value=arr.anio[a-1];
  document.getElementById("serie").value=arr.serie[a-1];
  document.getElementById("pais2").value=arr.pais[a-1];
  document.getElementById("estado3").value=arr.estado[a-1];
  document.getElementById("dueno").value=arr.dueno[a-1];
  document.getElementById("nombre_dueno").value=arr.nombre_dueno[a-1];
  document.getElementById("rfc_dueno").value=arr.rfc_dueno[a-1];
  document.getElementById("relacion").value=arr.relacion[a-1];
  document.getElementById("otra_relacion").value=arr.otra_relacion[a-1];
  document.getElementById("observaciones").value=arr.observaciones[a-1];

  var ubicacion=document.getElementsByName("ubicacion");
  for (var i = ubicacion.length - 1; i >= 0; i--) {
    if(ubicacion[i].value==arr.ubicacion[a-1]){
      ubicacion[i].checked="checked";
    }
  }

  var ubicacion2=document.getElementsByName("ubicacion2");
  for (var i = ubicacion2.length - 1; i >= 0; i--) {
    if(ubicacion2[i].value==arr.ubicacion[a-1]){ubicacion2[i].checked="checked";}
}


  if(arr.movimiento[a-1]=='B'){
    $("#baja").prop("checked",true);
  }
  else{
    $("#baja").prop("checked",false);
  }
  if(arr.movimiento[a-1]=='N'){
    $("#ninguno").prop("checked",true);
  }
  else{
    $("#ninguno").prop("checked",false);
  }
}
function form16_datos(arr,a){
  document.getElementById("pendientes").innerHTML=arr.html[a-1];
  document.getElementById("secuencia").value=arr.secuencia[a-1];
  document.getElementById("movimiento").value=arr.movimiento[a-1];
  document.getElementById("declarante").value=arr.declarante[a-1];
  document.getElementById("nombre_empresa").value=arr.nombre_empresa[a-1];
  document.getElementById("rfc_empresa").value=arr.rfc_empresa[a-1];
  document.getElementById("pct_participacion").value=arr.pct_participacion[a-1];
  document.getElementById("tipo_participacion").value=arr.tipo_participacion[a-1];
  document.getElementById("otra_participacion").value=arr.otra_participacion[a-1];
  document.getElementById("monto_mensual").value=arr.monto_mensual[a-1];
  document.getElementById("estado").value=arr.estado[a-1];
  document.getElementById("pais").value=arr.pais[a-1];
  document.getElementById("sector-pert").value=arr.sector[a-1];
  document.getElementById("otro_sector").value=arr.otro_sector[a-1];
  document.getElementById("observaciones").value=arr.observaciones[a-1];
  separador(document.getElementById("monto_mensual"));

  var ubicacion=document.getElementsByName("ubicacion");
  for (var i = ubicacion.length - 1; i >= 0; i--) {
    if(ubicacion[i].value==arr.ubicacion[a-1]){
      ubicacion[i].checked="checked";
      if(ubicacion[i].value == "M")ubicacion_m1();
      if(ubicacion[i].value == "E")ubicacion_e1();
    }
  }
  var remuneracion=document.getElementsByName("remuneracion");
  for (var i = remuneracion.length - 1; i >= 0; i--) {
    if(remuneracion[i].value==arr.remuneracion[a-1]){
      remuneracion[i].checked="checked";
      if(remuneracion[i].value == "S")remuneracion_s();
      if(remuneracion[i].value == "N")remuneracion_n();
    }
  }

  if(arr.movimiento[a-1]=='B'){
    $("#baja").prop("checked",true);
  }
  else{
    $("#baja").prop("checked",false);
  }
  if(arr.movimiento[a-1]=='N'){
    $("#ninguno").prop("checked",true);
  }
  else{
    $("#ninguno").prop("checked",false);
  }
}

function form17_datos(arr,a){
  document.getElementById("pendientes").innerHTML=arr.html[a-1];
  document.getElementById("secuencia").value=arr.secuencia[a-1];
  document.getElementById("movimiento").value=arr.movimiento[a-1];
  document.getElementById("declarante").value=arr.declarante[a-1];
  document.getElementById("tipo_institucion").value=arr.tipo_institucion[a-1];
  document.getElementById("otra_institucion").value=arr.otra_institucion[a-1];
  document.getElementById("nombre_inst").value=arr.nombre_inst[a-1];
  document.getElementById("rfc_inst").value=arr.rfc_inst[a-1];
  document.getElementById("puesto_descr").value=arr.puesto_descr[a-1];
  document.getElementById("fecha_inicio").value=arr.fecha_inicio[a-1];
  document.getElementById("monto_mensual").value=arr.monto_mensual[a-1];
  document.getElementById("estado").value=arr.estado[a-1];
  document.getElementById("pais").value=arr.pais[a-1];
  document.getElementById("observaciones").value=arr.observaciones[a-1];
  separador(document.getElementById("monto_mensual"));

  var ubicacion=document.getElementsByName("ubicacion");
  for (var i = ubicacion.length - 1; i >= 0; i--) {
    if(ubicacion[i].value==arr.ubicacion[a-1]){
      ubicacion[i].checked="checked";
    }
  }
  var remuneracion=document.getElementsByName("remuneracion");
  for (var i = remuneracion.length - 1; i >= 0; i--) {
    if(remuneracion[i].value==arr.remuneracion[a-1]){
      remuneracion[i].checked="checked";
    }
  }
  if(arr.movimiento[a-1]=='B'){
    $("#baja").prop("checked",true);
  }
  else{
    $("#baja").prop("checked",false);
  }
  if(arr.movimiento[a-1]=='N'){
    $("#ninguno").prop("checked",true);
  }
  else{
    $("#ninguno").prop("checked",false);
  }
}
function form18_datos(arr,a){
  document.getElementById("pendientes").innerHTML=arr.html[a-1];
  document.getElementById("secuencia").value=arr.secuencia[a-1];
  document.getElementById("movimiento").value=arr.movimiento[a-1];
  document.getElementById("beneficiario").value=arr.beneficiario[a-1];
  document.getElementById("otro_beneficiario").value=arr.otro_beneficiario[a-1];
  document.getElementById("nombre_prog").value=arr.nombre_prog[a-1];
  document.getElementById("instit_otorgante").value=arr.instit_otorgante[a-1];
  document.getElementById("orden").value=arr.orden_id[a-1];
  document.getElementById("tipo_apoyo").value=arr.tipo_apoyo[a-1];
  document.getElementById("otro_apoyo").value=arr.otro_apoyo[a-1];
  document.getElementById("monto_mensual").value=arr.monto_mensual[a-1];
  document.getElementById("apoyo_descr").value=arr.apoyo_descr[a-1];
  document.getElementById("observaciones").value=arr.observaciones[a-1];
  separador(document.getElementById("monto_mensual"));

  var forma_recep=document.getElementsByName("forma_recep");
  for (var i = forma_recep.length - 1; i >= 0; i--) {
    if(forma_recep[i].value==arr.forma_recep[a-1]){
      forma_recep[i].checked="checked";
    }
  }
  if(arr.movimiento[a-1]=='B'){
    $("#baja").prop("checked",true);
  }
  else{
    $("#baja").prop("checked",false);
  }
  if(arr.movimiento[a-1]=='N'){
    $("#ninguno").prop("checked",true);
  }
  else{
    $("#ninguno").prop("checked",false);
  }
}
function form19_datos(arr,a){
  document.getElementById("pendientes").innerHTML=arr.html[a-1];
  document.getElementById("secuencia").value=arr.secuencia[a-1];
  document.getElementById("movimiento").value=arr.movimiento[a-1];
  document.getElementById("declarante").value=arr.declarante[a-1];
  document.getElementById("tipo_repres").value=arr.tipo_repres[a-1];
  document.getElementById("fecha_inicio").value=arr.fecha_inicio[a-1];
  document.getElementById("representa").value=arr.representa[a-1];
  document.getElementById("nombre_repre").value=arr.nombre_repre[a-1];
  document.getElementById("rfc_repre").value=arr.rfc_repre[a-1];
  document.getElementById("monto_mensual").value=arr.monto_mensual[a-1];
  document.getElementById("estado").value=arr.estado[a-1];
  document.getElementById("pais").value=arr.pais[a-1];
  document.getElementById("sector-pert").value=arr.sector[a-1];
  document.getElementById("otro_sector").value=arr.otro_sector[a-1];
  document.getElementById("observaciones").value=arr.observaciones[a-1];
  separador(document.getElementById("monto_mensual"));

  var ubicacion=document.getElementsByName("ubicacion");
  for (var i = ubicacion.length - 1; i >= 0; i--) {
    if(ubicacion[i].value==arr.ubicacion[a-1]){
      ubicacion[i].checked="checked";
    }
  }
  var remuneracion=document.getElementsByName("remuneracion");
  for (var i = remuneracion.length - 1; i >= 0; i--) {
    if(remuneracion[i].value==arr.remuneracion[a-1]){
      remuneracion[i].checked="checked";
    }
  }

  if(arr.movimiento[a-1]=='B'){
    $("#baja").prop("checked",true);
  }
  else{
    $("#baja").prop("checked",false);
  }
  if(arr.movimiento[a-1]=='N'){
    $("#ninguno").prop("checked",true);
  }
  else{
    $("#ninguno").prop("checked",false);
  }
}
function form20_datos(arr,a){
  document.getElementById("pendientes").innerHTML=arr.html[a-1];
  document.getElementById("secuencia").value=arr.secuencia[a-1];
  document.getElementById("movimiento").value=arr.movimiento[a-1];
  document.getElementById("declarante").value=arr.declarante[a-1];
  document.getElementById("nombre_empresa").value=arr.nombre_empresa[a-1];
  document.getElementById("rfc_empresa").value=arr.rfc_empresa[a-1];
  document.getElementById("cliente").value=arr.cliente[a-1];
  document.getElementById("nombre_cliente").value=arr.nombre_cliente[a-1];
  document.getElementById("rfc_cliente").value=arr.rfc_cliente[a-1];
  document.getElementById("sector-pert").value=arr.sector[a-1];
  document.getElementById("otro_sector").value=arr.otro_sector[a-1];
  document.getElementById("monto_mensual").value=arr.monto_mensual[a-1];
  document.getElementById("estado").value=arr.estado[a-1];
  document.getElementById("pais").value=arr.pais[a-1];
  document.getElementById("observaciones").value=arr.observaciones[a-1];
  separador(document.getElementById("monto_mensual"));

  var actividad=document.getElementsByName("actividad");
  for (var i = actividad.length - 1; i >= 0; i--) {
    if(actividad[i].value==arr.actividad[a-1]){
      actividad[i].checked="checked";
    }
  }
  var ubicacion=document.getElementsByName("ubicacion");
  for (var i = ubicacion.length - 1; i >= 0; i--) {
    if(ubicacion[i].value==arr.ubicacion[a-1]){
      ubicacion[i].checked="checked";
    }
  }

  if(arr.movimiento[a-1]=='B'){
    $("#baja").prop("checked",true);
  }
  else{
    $("#baja").prop("checked",false);
  }
  if(arr.movimiento[a-1]=='N'){
    $("#ninguno").prop("checked",true);
  }
  else{
    $("#ninguno").prop("checked",false);
  }
}
function form21_datos(arr,a){
  document.getElementById("pendientes").innerHTML=arr.html[a-1];
  document.getElementById("secuencia").value=arr.secuencia[a-1];
  document.getElementById("movimiento").value=arr.movimiento[a-1];
  document.getElementById("tipo_beneficio").value=arr.tipo_beneficio[a-1];
  document.getElementById("otro_beneficio").value=arr.otro_beneficio[a-1];
  document.getElementById("beneficiario").value=arr.beneficiario[a-1];
  document.getElementById("otro_beneficiario").value=arr.otro_beneficiario[a-1];
  document.getElementById("otorgante").value=arr.otorgante[a-1];
  document.getElementById("nombre_otorga").value=arr.nombre_otorga[a-1];
  document.getElementById("rfc_otorga").value=arr.rfc_otorga[a-1];
  document.getElementById("beneficio_descr").value=arr.beneficio_descr[a-1];
  document.getElementById("monto_mensual").value=arr.monto_mensual[a-1];
  document.getElementById("tipo_moneda").value=arr.tipo_moneda[a-1];
  document.getElementById("sector-pert").value=arr.sector[a-1];
  document.getElementById("otro_sector").value=arr.otro_sector[a-1];
  document.getElementById("observaciones").value=arr.observaciones[a-1];
  separador(document.getElementById("monto_mensual"));

  var forma_recep=document.getElementsByName("forma_recep");
  for (var i = forma_recep.length - 1; i >= 0; i--) {
    if(forma_recep[i].value==arr.forma_recep[a-1]){
      forma_recep[i].checked="checked";
    }
  }

  if(arr.movimiento[a-1]=='B'){
    $("#baja").prop("checked",true);
  }
  else{
    $("#baja").prop("checked",false);
  }
  if(arr.movimiento[a-1]=='N'){
    $("#ninguno").prop("checked",true);
  }
  else{
    $("#ninguno").prop("checked",false);
  }
}
function form22_datos(arr,a){
  document.getElementById("pendientes").innerHTML=arr.html[a-1];
  document.getElementById("secuencia").value=arr.secuencia[a-1];
  document.getElementById("movimiento").value=arr.movimiento[a-1];
  document.getElementById("declarante").value=arr.declarante[a-1];
  document.getElementById("tipo_fideicomiso").value=arr.tipo_fideicomiso[a-1];
  document.getElementById("como_participa").value=arr.como_participa[a-1];
  document.getElementById("rfc_fideicomiso").value=arr.rfc_fideicomiso[a-1];
  document.getElementById("fideicomitente").value=arr.fideicomitente[a-1];
  document.getElementById("nom_fideicomitente").value=arr.nom_fideicomitente[a-1];
  document.getElementById("rfc_fideicomitente").value=arr.rfc_fideicomitente[a-1];
  document.getElementById("nom_fiduciario").value=arr.nom_fiduciario[a-1];
  document.getElementById("rfc_fiduciario").value=arr.rfc_fiduciario[a-1];
  document.getElementById("fideicomisario").value=arr.fideicomisario[a-1];
  document.getElementById("nom_fideicomisario").value=arr.nom_fideicomisario[a-1];
  document.getElementById("rfc_fideicomisario").value=arr.rfc_fideicomisario[a-1];
  document.getElementById("sector-pert").value=arr.sector[a-1];
  document.getElementById("otro_sector").value=arr.otro_sector[a-1];
  document.getElementById("observaciones").value=arr.observaciones[a-1];

  var ubicacion=document.getElementsByName("ubicacion");
  for (var i = ubicacion.length - 1; i >= 0; i--) {
    if(ubicacion[i].value==arr.ubicacion[a-1]){
      ubicacion[i].checked="checked";
    }
  }

  if(arr.movimiento[a-1]=='B'){
    $("#baja").prop("checked",true);
  }
  else{
    $("#baja").prop("checked",false);
  }
  if(arr.movimiento[a-1]=='N'){
    $("#ninguno").prop("checked",true);
  }
  else{
    $("#ninguno").prop("checked",false);
  }
}

function pass_update(rfc){
      //console.log(rfc);
      $("#overlay").show();
      $("#cambio-password").show();
      $(".reset-pass").val("");
      $("#pass-aviso").hide();
      $("#rfc").val(rfc);
      //$(".search-table").empty();
      //$(".search-table").append(data);
}
function modificarinput(id,status){
      //console.log(id+" "+status);
       $("#elemento"+id).css("display","inline-block");    
       $("#actualizar"+id).css("display","inline-block");    
       $("#nombre-input"+id).css("display","none");    
       $("#modificar"+id).css("display","none");    
/*       $("input[type=radio][name=estatus-option"+id+"]").css("display","inline-block");*/
       $("#estatus-radio"+id).css("display","inline-block");    
       $("#estatus-input"+id).css("display","none");    
       if(status=="A"){
          $("#estatus-opt"+id+"1").prop('checked',true);
       }
       else{
          $("#estatus-opt"+id+"2").prop('checked',true);
       }
}

function sumaingresos(){
  var a= document.getElementById("activ_industrial").value.replace(/,/g,"");
  var b= document.getElementById("activ_financiera").value.replace(/,/g,"");
  var c= document.getElementById("serv_profesionales").value.replace(/,/g,"");
  if(document.getElementById("enajena_bienes"))
    var d= document.getElementById("enajena_bienes").value.replace(/,/g,"");
  else
    var d=0;
  var e= document.getElementById("no_considerados").value.replace(/,/g,"");
  //console.log(a);
  if(a=='')a='0';
  if(b=='')b='0';
  if(c=='')c='0';
  if(d=='')d='0';
  if(e=='')e='0';
  if(f=='')f='0';
  var f= parseInt(a) + parseInt(b) + parseInt(c) + parseInt(d) + parseInt(e);
  $("#otros_ingresos").val(f);

  var g= document.getElementById("remunera_neta").value.replace(/,/g,"");
  var h= document.getElementById("otros_ingresos").value.replace(/,/g,"");
  if(g=='')g='0';
  if(h=='')h='0';

  var i= parseInt(g) + parseInt(h);
  $("#ingreso_neto").val(i);  
}

function separador(object){
  //console.log(object.value);
    if(object.value.replace(/,/g,"")<=2000000000){
        $(object).val(function (index, value ) {
            return value.replace(/\D/g, "")
/*                  .replace(/([0-9])([0-9]{2})$/, '$1.$2')*/
                    .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",");
        });

  }
  else {
        $(object).val(function (index, value ) {
          var maximo="2,000,000,000";
            return maximo;
        });
  }
}
function separador_punto(object){
  //console.log(object.value);
    if(object.value.replace(/,/g,"")<=2000000000){
        $(object).val(function (index, value ) {
            return value.replace(/\D/g, "")
                  .replace(/([0-9])([0-9]{2})$/, '$1.$2')
                    .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",");
        });

  }
  else {
        $(object).val(function (index, value ) {
          var maximo="2,000,000,000.00";
            return maximo;
        });
  }
}
function separador_sup(object){
  //console.log(object.value);
    if(object.value.replace(/,/g,"")<=9999999.99){
        $(object).val(function (index, value ) {
            return value.replace(/\D/g, "")
                  .replace(/([0-9])([0-9]{2})$/, '$1.$2')
                    .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",");
        });
  }
  else {
        $(object).val(function (index, value ) {
          var maximo="9,999,999.99";
            return maximo;
        });
  }
}
function hidecambio(){
      $("#overlay").hide();
      $("#aceptar-cambio").hide();  
}


$(window).on("load",efectos);
