// MUDAR PÁGINA----------------------------------------------------------------------------------------------
$("a#pg1").click(function () {

  $("#tab1").animate({
    opacity: 1
  }, 500).show();
  $("#tab2").animate({
    opacity: 0
  }, 500).hide();
  $("#tab3").animate({
    opacity: 0
  }, 500).hide();
  $("#tab4").animate({
    opacity: 0
  }, 500).hide();

  addClassGeral(1);

});

$("a#pg2").click(function () {

  $("#tab2").animate({
    opacity: 1
  }, 500).show();
  $("#tab1").animate({
    opacity: 0
  }, 500).hide();
  $("#tab3").animate({
    opacity: 0
  }, 500).hide();
  $("#tab4").animate({
    opacity: 0
  }, 500).hide();

  addClassGeral(2);

});

$("a#pg3").click(function () {

  $("#tab3").animate({
    opacity: 1
  }, 500).show();
  $("#tab1").animate({
    opacity: 0
  }, 500).hide();
  $("#tab2").animate({
    opacity: 0
  }, 500).hide();
  $("#tab4").animate({
    opacity: 0
  }, 500).hide();

  addClassGeral(3);

});

$("a#pg4").click(function () {

  $("#tab4").animate({
    opacity: 1
  }, 500).show();
  $("#tab1").animate({
    opacity: 0
  }, 500).hide();
  $("#tab2").animate({
    opacity: 0
  }, 500).hide();
  $("#tab3").animate({
    opacity: 0
  }, 500).hide();

  addClassGeral(4);

});

//--------------------------------------------------------------------------------------------------------------
function addClassGeral(classe) {
  if (classe == 1)
    $("a#pg1").addClass("active");
  else
    $("a#pg1").removeClass("active");

  if (classe == 2)
    $("a#pg2").addClass("active");
  else
    $("a#pg2").removeClass("active");

  if (classe == 3)
    $("a#pg3").addClass("active");
  else
    $("a#pg3").removeClass("active");

  if (classe == 4)
    $("a#pg4").addClass("active");
  else
    $("a#pg4").removeClass("active");
}
//--------------------------------------------------------------------------------------------------------------