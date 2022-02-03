var field_count = 0;
var loading = false;
var reply_id = false;
var repost_id = false;
var thread_id = false;

var current_page;
var version = "v1";

var activity_message;
var original_title = document.title;

var messages_check;
var current_convo;

var content_id;
if (!emojis) var emojis = false;

$.ajaxSetup({
  cache: false // for IE
});

$(window).on('load', function () {
  activate(current_page);
  lazyLoad();
  userSearch();
  changeIcon("/img/icon.png");
  setTimeout(function () {
    $(".site-progress").fadeOut();
  }, 10000);
  setTimeout(function () {
    $(".preload").fadeOut();
    $("body").removeClass("hinder");
  }, 1000);
});

$(function () {
  if (getCookie("back") || !getCookie("session")) {
    $("body").removeClass("hinder");
  }
  eraseCookie("back");
  loadFont('Ubuntu');
  $.ajax({
    type: "POST",
    url: "/backend",
    data: {
      action: "languages"
    },
    success: function (response) {
      $(".lang-list").html(response);
    },
    error: function (data) {
      console.log("error loading languages");
      $(".lang-list").html("<div class='empty-state-message'>" + t('Error loading languages.') + "</div>");
    }
  });
  if (getCookie("session")) {
    setInterval(function () {
      $.ajax({
        type: "POST",
        url: "/api/" + version + "/checkLimit",
        dataType: "json",
        success: function (response) {
          var status = response.status;
          var message = response.message;
          $("#limit-status").html(message);
          if (status === "reached") {
            modals('unplug');
            newAlert(message);
          } else if (status === "logout") {
            window.location = '/accounts/logout';
          }
        },
        error: function (data) {
          console.log("limit error");
          $("#limit-status").html(t("Error checking time limit."));
        }
      });
      $.ajax({
        type: "POST",
        url: "/api/" + version + "/checkActivity",
        dataType: "json",
        success: function (response) {
          var status = response.status;
          var message = response.message;
          console.log(status);
          if (status === "new") {
            $(".nav-btn-activity").addClass("new-activity");
            changeIcon("/img/alerts.png");
            var matches = message.match(/(\d+)/);
            document.title = "(" + matches[0] + ") " + original_title;
            if (activity_message !== message) {
              activity_message = message;
              newAlert(message);
            }
          } else {
            $(".nav-btn-activity").removeClass("new-activity");
            changeIcon("/img/icon.png");
            document.title = original_title;
          }
        },
        error: function (data) {
          console.log("activity error");
        }
      });
    }, 5000);
  }
  setInterval(function () {
    // check for new posts
    if (current_page === "home") {

    }
    if (current_page === "thread") {

    }
  }, 10000);
  if (!emojis) {
    $(".back-link").on('click', function (e) {
      window.location = '/';
    });
  }
  $("#gif-search").on('change', function (e) {
    $("#gifs-error").fadeOut();
    var query = $(this).val();
    if (query) {
      $.ajax({
        type: "POST",
        url: "/backend",
        data: {
          action: "gifs",
          query: query
        },
        success: function (response) {
          if (response) {
            $(".gif-results").html(response);
            lazyLoad();
            $(".gif-item").click(function () {
              var url = $(this).attr("data-gif");
              addImage(url);
              modalSwitch('attach-gif', 'new-post');
            });
          } else {
            $(".gif-results").html(t('No clever GIFs for') + " '" + query + "'.");
          }
        },
        error: function (data) {
          $("#gifs-error").fadeIn();
        }
      });
    }
  });
  $("#user-search").on('change', function (e) {
    userSearch();
  });
  $("#convo-users").on('change', function (e) {
    if ($(this).val()) {
      $(".new-convo-btn").prop("disabled", false);
    } else {
      $(".new-convo-btn").prop("disabled", true);
    }
  });
  $("#quick-search").on('keyup', function (e) {
    var query = $(this).val();
    if (query) {
      $(".search-results").fadeIn();
      $.ajax({
        type: "POST",
        url: "/backend",
        data: {
          action: "search",
          query: query
        },
        success: function (response) {
          $(".search-results").html(response);
          $(".search-results .quick-search-item").click(function () {
            var final_query = $(this).text();
            $.ajax({
              type: "POST",
              url: "/api/" + version + "/querySave",
              data: {
                query: query,
                final: final_query
              },
              success: function (response) {
                // query saved
              },
              error: function (data) {
                console.log("error saving query");
              }
            });
          });
          activate();
        },
        error: function (data) {
          console.log("error searching");
        }
      });
    } else {
      $(".search-results").fadeOut();
    }
  });
  $(".photo-btn").click(function () {
    $("#postUpload").click();
  });
  $("#postUpload").change(function () {
    var fd = new FormData();
    var files = $('#postUpload')[0].files[0];
    fd.append('action', 'posts');
    fd.append('file', files);
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/img/upload', true);
    $(":input, :button").prop('disabled', true);
    $(".indeterminate").hide();
    $(".determinate").show();
    $(".upload-photo").show();
    xhr.upload.onprogress = function (e) {
      if (e.lengthComputable) {
        var percentComplete = (e.loaded / e.total) * 100;
        $(".progress-text").html("Uploading..." + Math.round(percentComplete) + '%');
        $(".determinate").css('width', Math.round(percentComplete) + '%');
      }
    };
    xhr.onload = function () {
      if (this.status === 200) {
        var response = this.responseText;
        $(":input, :button").prop('disabled', false);
        $(".upload-photo").hide();
        console.log(response);
        if (response === "badext") {
          var uploadResult = "Only .jpeg, .png and .gif files are allowed.";
        } else if (response === "toolarge") {
          var uploadResult = "Your file is too large for us (yourimage > 500 MB)";
        } else if (response.includes("error")) {
          var uploadResult = "We couldn't upload that photo for some reason, please try again later.";
        } else {
          var uploadResult = "Photo uploaded!";
          addImage("/img/posts/" + response);
        }
        newAlert(t(uploadResult));
        activate();
      } else {
        console.log("goofed");
        $(":input, :button").prop('disabled', false);
        $(".upload-photo").hide();
        newAlert(t("We couldn't upload that photo for some reason, please try again later."));
      }
    };
    xhr.send(fd);
  });
  $(".set-limit-btn").click(function () {
    $("#limit-error").fadeOut();
    var limit = $("select#limit-range option:selected").val();
    var logout = $("#logout").prop("checked");
    $(":input, :button").prop('disabled', true);
    $.ajax({
      type: "POST",
      url: "/api/" + version + "/limit",
      dataType: "json",
      data: {
        limit: limit,
        logout: logout
      },
      success: function (response) {
        var status = response.status;
        var message = response.message;
        $(":input, :button").prop('disabled', false);
        if (status === "success") {
          modals('limit');
          newAlert(message);
        } else {
          $("#limit-error").html(message);
          $("#limit-error").fadeIn();
        }
      },
      error: function (data) {
        $(":input, :button").prop('disabled', false);
        $("#limit-error").html(t("Error setting limit, please try again later."));
        $("#limit-error").fadeIn();
      }
    });
  });
  $("#password").on('input', function () {
    var strength = {
      0: "Keep going...",
      1: "Not a safe password. Add a number and a special character.",
      2: "It could be better. Add another number or a special character.",
      3: "A fair password.",
      4: "A truly divine password!"
    };
    var password = $("#password").val();
    var confirm = $("#passwordconfirm").val();
    var meter = document.getElementById('password-strength-meter');
    var result = zxcvbn(password); // Update the password strength meter
    meter.value = result.score; // Update the text indicator
    if (password !== "") {
      $(".password-strength").html(t(strength[result.score]));
    } else {
      $(".password-strength").html(t("Use at least 8 characters, 1 number and 1 special character."));
    }
  });
  $({
    property: 0
  }).animate({
    property: 105
  }, {
    duration: 4000,
    step: function () {
      var _percent = Math.round(this.property);
      $(".site-progress").css("width", _percent + "%");
      if (_percent == 100) {
        $(".site-progress").addClass("progress-done");
        $(".site-progress").remove();
      }
    },
    complete: function () {
      // Page has finished loading
    }
  });
  // Sign up / Login
  $("#login-form").on("submit", function (event) {
    event.preventDefault();
    $("#login-error").fadeOut();
    $(".require-error").fadeOut();
    $(".login-inputs").removeClass("wrong-password");
    var checkinputs = checkInputs(this.id);
    var checklimits = checkCount(this.id);
    var form = $("form#" + this.id);
    var formdata = form.serialize();
    if (checkinputs === true && checklimits === true) {
      $(":input, :button").prop('disabled', true);
      $.ajax({
        type: "POST",
        url: "/accounts/auth",
        data: formdata,
        success: function (response) {
          if (response === "go") {
            window.location = "/";
          } else {
            $(":input, :button").prop('disabled', false);
            $("#login-error").html(response);
            $("#login-error").fadeIn();
            if (response === "Incorrect Password") {
              $(".login-inputs").addClass("wrong-password");
            }
            $("iframe").attr("src", $("iframe").attr("src"));
          }
        },
        error: function (data) {
          $(":input, :button").prop('disabled', false);
          $("#login-error").html("Error logging you in, please try again later.");
          $("#login-error").fadeIn();
        }
      });
    } else { // do nothing, the error messages are handled by another function
      $(":input, :button").prop('disabled', false);
      console.log("Required inputs are empty or an input is beyond it's character limit");
    }
    event.preventDefault();
  });
  $("#signup-form").on("submit", function (event) {
    event.preventDefault();
    $("#signup-error").fadeOut();
    var checkinputs = checkInputs(this.id);
    var checklimits = checkCount(this.id);
    var form = $("form#" + this.id);
    var formdata = form.serialize();
    if (checkinputs === true && checklimits === true) {
      $(":input, :button").prop('disabled', true);
      $.ajax({
        type: "POST",
        url: "/accounts/auth",
        data: formdata,
        success: function (response) {
          $(":input, :button").prop('disabled', false);
          if (response === "go") {
            window.location = "/settings/privacy";
          } else {
            $("#signup-error").html(response);
            $("#signup-error").fadeIn();
          }
        },
        error: function (data) {
          $(":input, :button").prop('disabled', false);
          $("#signup-error").html("Backend error creating your account.");
          $("#signup-error").fadeIn();
        }
      });
    } else { // do nothing, the error messages are handled by another function
      console.log("Required inputs are empty or an input is beyond it's character limit");
      $(":input, :button").prop('disabled', false);
    }
    event.preventDefault();
  });
  $("body").on("click", function (event) {
    $(".search-results").fadeOut();
  });
  $("#new-post-form").on("submit", function (event) {
    event.preventDefault();
    $("#newpost-error").fadeOut();
    var checkinputs = checkInputs(this.id);
    var checklimits = checkCount(this.id);
    var form = $("form#" + this.id);
    var formdata = form.serialize();
    if (reply_id !== false) formdata += "&reply=" + reply_id;
    if (repost_id !== false) formdata += "&repost=" + repost_id;
    if (thread_id !== false) formdata += "&thread=" + thread_id;
    if (checkinputs === true && checklimits === true) {
      $(":input, :button").prop('disabled', true);
      $.ajax({
        type: "POST",
        url: "/api/" + version + "/newPost",
        data: formdata,
        dataType: "json",
        success: function (response) {
          var status = response.status;
          var message = response.message;
          $(":input, :button").prop('disabled', false);
          if (status === "success") {
            if (current_page === "home") content(".posts-container", "feed", "type=home&weeks=1");
            if (current_page === "thread") content("#thread-replies", "feed", "type=thread&thread=" + content_id + "&weeks=1");
            if (current_page === "post") content(".posts-container", "feed", "type=replies&post=" + content_id + "&weeks=1");
            newAlert(message);
            modals("new-post");
            reply_id = false;
            repost_id = false;
            thread_id = false;
            $("#content").val("");
            $(".modal#new-post h2").html("New Post");
            $(".ref-post").html("");
          } else {
            $("#newpost-error").html(message);
            $("#newpost-error").fadeIn();
          }
        },
        error: function (data) {
          $(":input, :button").prop('disabled', false);
          $("#newpost-error").html("Something went wrong sending this post.");
          $("#newpost-error").fadeIn();
        }
      });
    } else { // do nothing, the error messages are handled by another function
      console.log("Required inputs are empty or an input is beyond it's character limit");
      $(":input, :button").prop('disabled', false);
    }
    event.preventDefault();
  });
  $(".dropdown").each(function () {
    $(this).append("<div class='dropdown-divider'></div><ul><li class='cancel-opt'>" + t("Close") + "</li></ul>");
  });
  $(".new-convo-btn").click(function () {
    var users = $("#convo-users").val();
    if (users) {
      $.ajax({
        type: "POST",
        url: "/api/" + version + "/newConvo",
        dataType: "json",
        data: {
          users: users
        },
        success: function (response) {
          var status = response.status;
          var message = response.message;
          if (status === "success") {
            load("messages", "id=" + message, "messages/" + message);
            modals("new-dm");
            resetDM();
          } else {
            $("#usersearch-error").html(message);
            $("#usersearch-error").fadeIn();
          }
        },
        error: function (data) {
          console.log("error creating new convo");
          $("#usersearch-error").html("There was an error creating a new conversation.");
          $("#usersearch-error").fadeIn();
        }
      });
    }
  });
  $(window).on("popstate", function (e) {
    setCookie("back", true, 1);
    location.reload();
  });
});

function addImage(url) {
  $(".attach-media").fadeIn();
  if ($("#new-post-image").val()) {
    var newurl = $("#new-post-image").val() + "," + url;
    $("#new-post-image").val(newurl);
  } else {
    $("#new-post-image").val(url);
  }
  $(".attach-media").append("<div class='media-item' data-background='" + url + "'><div class='remove-media' data-url='" + url + "'></div></div>");
  $(".remove-media").on('click', function (e) {
    var media = $("#new-post-image").val();
    var newurls = removeValue(media, $(this).attr("data-url"));
    var url = $(this).attr("data-url");
    $("#new-post-image").val(newurls);
    $(".media-item[data-background='" + url + "']").remove();
    if (!$(".attach-media > .media-item").length) {
      $(".attach-media").fadeOut();
    }
  });
  lazyLoad();
}

function addMessage(id, convo, content = false) {
  if (content) {
    $("[data-convo=" + convo + "]").append("<div class='message-placeholder sending' id='mplaceholder-" + id + "'><div class='message-container sender-container' id='message-" + id + "'><div class='message-item message-sender' id='message-" + id + "'>" + content + "</div><div class='message-timestamp'>Sending...</div></div></div>");
  } else {
    $.ajax({
      type: "POST",
      url: "/backend",
      data: {
        action: "getMessage",
        id: id,
        convo: convo
      },
      success: function (response) {
        if (response !== "error") {
          $("#mplaceholder-" + id).html(response);
          $("#mplaceholder-" + id).removeClass("sending");
          scrollMessages();
        } else {
          // error loading message
        }
      },
      error: function (data) {
        console.log("error getting message");
      }
    });
  }
}

function scrollMessages() {
  var d = $(".messages-container");
  d.scrollTop(d.prop("scrollHeight"));
}

function resetPost() {
  $("#content").val("");
  $("#limit-content").html("0 / 500");
  $(".attach-media").html("");
  $(".attach-media").fadeOut();
  $(".post-privacy").show();
  $(".reply-privacy").hide();
  $("#new-post-image").val("");
}

function resetDM() {
  userSearch();
  $("#user-search").val("");
  $("#convo-users").val("");
  $(".new-convo-btn").prop("disabled", true);
  $("#usersearch-error").fadeOut();
}

function refreshPrivacy(pub = false) {
  $.ajax({
    type: "POST",
    url: "/backend",
    data: {
      action: "privacy",
      public: pub
    },
    success: function (response) {
      $(".post-privacy").html(response);
      if (!$("select#post-privacy").val()) {
        $("select#post-privacy").val("1");
      } else {
        $("select#post-privacy").val("");
      }
      $(".toggle-privacy").click(function () {
        if (!$("#post-privacy").val()) index = 1;
        else index = 0;
        refreshPrivacy(index);
      });
    },
    error: function (data) {
      console.log("error getting privacy status");
    }
  });
}

function modals(id) {
  if ($(".modal:not(#" + id + ")").is(":visible")) {
    $($(".modal:not(#" + id + ")").is(":visible")).removeClass("scale");
    $($(".modal:not(#" + id + ")").is(":visible")).fadeOut();
    $(".modals-container").fadeOut();
  }
  if (id === "new-post") {
    refreshPrivacy();
  }
  $("html,body").toggleClass("hinder");
  if (!$(".modal#" + id).hasClass("scale")) {
    $(".modal#" + id).fadeToggle({
      duration: 100
    });
    setTimeout(function () {
      $(".modal#" + id).toggleClass("scale", {
        duration: 30,
        easing: "easeInOutQuart"
      });
    }, 10);
    $(".modals-container").css("display", "flex");
    if (id === "new-post") {
      $("#content").focus();
    }
  } else {
    $(".modal#" + id).toggleClass("scale", {
      duration: 30,
      easing: "easeInOutQuart"
    });
    setTimeout(function () {
      $(".modal#" + id).fadeToggle({
        duration: 100
      });
      $(".modals-container").fadeToggle();
    }, 10);
  }
}

function modalSwitch(close, open) {
  modals(close);
  setTimeout(function () {
    modals(open);
  }, 500);
}

document.head = document.head || document.getElementsByTagName('head')[0];

function changeIcon(src) {
  var link = document.createElement('link'),
    oldLink = document.getElementById('dynamic-favicon');
  link.id = 'dynamic-favicon';
  link.rel = 'shortcut icon';
  link.href = src;
  if (oldLink) {
    document.head.removeChild(oldLink);
  }
  document.head.appendChild(link);
}

const loadFont = (url) => { // the 'fetch' equivalent has caching issues
  var url = "https://fonts.googleapis.com/css?family=" + url + ":400,500,600,700";
  var xhr = new XMLHttpRequest();
  xhr.open('GET', url, true);
  xhr.onreadystatechange = () => {
    if (xhr.readyState == 4 && xhr.status == 200) {
      let css = xhr.responseText;
      css = css.replace(/}/g, 'font-display: swap; }');
      const head = document.getElementsByTagName('head')[0];
      const style = document.createElement('style');
      style.appendChild(document.createTextNode(css));
      head.appendChild(style);
    }
  };
  xhr.send();
}

function runtooltips() {
  $('body').tooltip({
    selector: '[data-toggle=tooltip]',
    trigger: 'hover',
    container: 'body'
  });
}

function filter() {
  var val = $("#edit-val-profanity").val();
  if (val === "0") {
    var label = "None";
    var desc = t("Live life on the edge. Don't censor any profanity, slurs, and offensive or sexual language.");
  } else if (val === "1") {
    var label = "Moderate";
    var desc = t("Censor slurs and offensive language.");
  } else if (val === "2") {
    var label = "High";
    var desc = t("Censor swears, slurs, and sexual language.");
  } else if (val === "3") {
    var label = "Extreme";
    var desc = t("Censor swears, slurs, and sexual language. Even in the most devious of spellings. This is a christian household.");
  }
  $("#filter-title").html(label);
  $("#filter-desc").html(desc);
}

function openVideo(id, vid) {
  $(".post-link-block#" + id).hide();
  $("#frame" + id + " iframe").attr("src", "https://youtube.com/embed/" + vid + "?autoplay=1");
  $("#frame" + id).show();
}

function updateLimits(ele) {
  var thislength = $(ele).val().length;
  var limit = $(ele).attr('data-limit');
  var half = limit / 2;
  if (thislength > half && thislength < limit) {
    $(this).removeClass("require-input");
    $("#limit-" + ele.id).addClass("limit-almost");
    $("#limit-" + ele.id).removeClass("limit-reached");
  } else if (thislength > limit) {
    $(this).addClass("require-input");
    $("#limit-" + ele.id).addClass("limit-reached");
  } else {
    $(this).removeClass("require-input");
    $("#limit-" + ele.id).removeClass("limit-reached");
    $("#limit-" + ele.id).removeClass("limit-almost");
  }
  $("#limit-" + ele.id).html(thislength + " / " + limit);
}

var confirmunload;

// Load content and pages
function load(action, data, url) {
  if (loading === false) {
    var navigate;
    if (confirmunload === true) {
      var c = confirm("Are you sure you want to leave? Any unsaved changes will be lost.");
      if (c === true) {
        navigate = true;
      } else {
        navigate = false;
        setTimeout(function () {
          $(".active-tab").removeClass("active-tab");
        }, 50);
      }
    } else navigate = true;
    if (navigate === true) {
      if (action === "create-thread") {
        confirmunload = true;
        window.onbeforeunload = function () {
          return true;
        };
      } else {
        window.onbeforeunload = null;
        confirmunload = false;
      }
      if (action && (action !== current_page || action === "post" || action === "profile" || action === "messages")) {
        loading = true;
        current_page = action;
        var data = data;
        var initdata = data;
        if (action === "home") url = "/";
        if (!url) {
          url = "/" + action;
        }
        if (data) {
          data = data + "&action=" + action;
        } else {
          data = "action=" + action;
        }
        $(".left-container").html("<div class='inline-loading'><div class='load'></div></div>");
        if (action !== "messages") {
          //$(".left-container, .right-container").html("<div class='inline-loading'><div class='load'></div></div>");
        }
        $.ajax({
          type: "POST",
          url: "/page",
          data: data,
          success: function (response) {
            loading = false;
            $(".left-container").html(response);
            activate(action);
            setTitle(action, initdata);
            window.history.pushState({
              page: action
            }, action, url);
            $('html, body').animate({
              scrollTop: $('.page-container').offset().top - 150
            }, 'fast');
            if (action !== "messages") {
              $("body").removeClass("hinder");
              $(".new-post").fadeIn();
              clearInterval(messages_check);
            }
          },
          error: function (data) {
            console.log(data);
            $(".left-container").html("<div class='content-block'>Error getting page...</div>");
          }
        });
        if ($(".right-container").length) {
          if (data) {
            data = initdata + "&action=side&main=" + action;
          } else {
            data = "action=side&main=" + action;
          }
          $.ajax({
            type: "POST",
            url: "/page",
            data: data,
            success: function (response) {
              $(".right-container").html(response);
              activate(action);
              content(".trending-container", "threads", "type=trending");
            },
            error: function (data) {
              console.log(data);
              $(".right-container").html("<div class='content-block'>Error</div>");
            }
          });
        }
      }
    }
  }
}

function setTitle(page, data) {
  if (data) {
    data = data + "&page=" + page;
  } else {
    data = "page=" + page;
  }
  data = data + "&simple=true";
  $.ajax({
    type: "POST",
    url: "/metadata",
    data: data,
    success: function (response) {
      document.title = response + " - Pengin";
      original_title = response + " - Pengin";
    },
    error: function (data) {
      console.log("Error setting page title...");
    }
  });
}

function content(selector, action, data) {
  if (!$(selector).html()) {
    $(selector).html("<div class='inline-loading'><div class='load'></div></div>");
  }
  data += "&action=" + action;
  $.ajax({
    type: "POST",
    url: "/backend",
    data: data,
    success: function (response) {
      $(selector).html(response);
      activate(action);
    },
    error: function (data) {
      $(selector).html("<div class='content-block'>Error loading content...</div>");
      console.log(data);
    }
  });
}

function activate(action) {
  lazyLoad();
  $(".tooltip").remove();
  $("[data-action],[data-modal],[data-activity],[data-dropdown],[data-copy],[data-blacklist],.accept-btn,.decline-btn,.report-opt,.reply-btn,.repost-btn,.send-msg-btn,.mask-btn,.lang-item").unbind();
  $("input, textarea").each(function () {
    updateLimits(this);
  });
  $(".lang-item").on('click', function (e) {
    newAlert(t("Changing Language") + "...");
    $("#edit-val-language").val($(this).attr("data-code"));
    saveEdit("language", true);
    setTimeout(function () {
      window.location = window.location.href;
    }, 1000);
  });
  $(".mask-btn").click(function () {
    $(this).toggleClass("mask-show");
    if ($("#password").attr("type") === "password") {
      $("#password").prop("type", "text");
    } else {
      $("#password").prop("type", "password");
    }
  });
  $("input, textarea").on('input', function () {
    updateLimits(this);
  });
  $("[data-modal]").click(function () {
    var modal = $(this).attr("data-modal");
    if ($(".modal#" + modal).length) {
      modals(modal);
    } else {
      console.log("Modal '" + modal + "' doesn't exist.");
    }
  });
  $("[tooltip]").each(function () {
    var tooltip = $(this).attr("tooltip");
    $(this).attr("data-toggle", "tooltip");
    $(this).attr("data-original-title", tooltip);
    if (!ismobile) {
      runtooltips();
    }
  });
  $("input[name=showdetails]").on('change', function () {
    var detailSel = document.getElementsByName('showdetails'); // Checkboxes : basically just choosing which calendars to find at the current time
    var details = ''; //Comma separated string of calendar types
    for (var i = 0, len = detailSel.length; i < len; i++) {
      if (detailSel[i].checked) {
        details += detailSel[i].value + ',';
      }
    }
    details = details.replace(/,$/, '');
    $("#edit-val-showdetails").val(details);
    saveEdit('showdetails', true);
  });
  $("[data-action]").click(function () {
    var action = $(this).attr("data-action");
    var args = $(this).attr("data-args");
    var url = $(this).attr("data-url");
    var action = $(this).attr("data-action");
    if (action === "profile" || action === "post" || action === "thread") {
      $(".active-tab").removeClass("active-tab");
    }
    load(action, args, url);
  });
  $(".navigation-logo").click(function () {
    $(".active-tab").removeClass("active-tab");
    $("li[data-action=home], .nav-btn-home").addClass("active-tab");
  });
  $("li[data-action=home], .nav-btn-home").click(function () {
    setTimeout(function () {
      $("li[data-action=home], .nav-btn-home").addClass("active-tab");
    }, 500);
  });
  $("li[data-action=discuss], .nav-btn-discuss").click(function () {
    setTimeout(function () {
      $("li[data-action=discuss], .nav-btn-discuss").addClass("active-tab");
    }, 500);
  });
  $(".navigation-tabs li, .navigation-btn").click(function () {
    var action = $(this).attr("data-action");
    if (action) {
      $(".active-tab").removeClass("active-tab");
      $(this).addClass("active-tab");
    }
  });
  $(".follow-btn").mouseover(function () {
    if ($(this).hasClass("active-follow")) {
      $(this).text(t("Unfollow"));
    }
  });
  $(".follow-btn").mouseleave(function () {
    if ($(this).hasClass("active-follow")) {
      $(this).text(t("Following"));
    }
  });
  $("[data-activity]").click(function () {
    var what = $(this).attr("data-activity");
    var content = $(this).attr("data-id");
    var target = $(this).attr("data-author");
    var selector = $(this).attr("data-selector");
    if (!selector) selector = this;
    var that = this;
    var confirmed;
    if (what === "follow" && $(this).hasClass("active-follow") || $(this).hasClass("unfollow-opt")) {
      confirmed = confirm("Are you sure you want to unfollow this person?");
    } else {
      confirmed = true;
    }
    if (confirmed === true) {
      $.ajax({
        type: "POST",
        url: "/api/" + version + "/activity",
        dataType: "json",
        data: {
          what: what,
          content: content,
          target: target
        },
        success: function (response) {
          var status = response.status;
          var message = response.message;
          var text = $(that).text();
          if (status === "success") {
            if (what !== "request" && what !== "follow") {
              $(selector).html(message);
            }
            if (what !== "reply") {
              $(that).toggleClass("active-" + what);
              $(that).toggleClass("active-action");
            }
            if (what === "request") {
              if (!$(that).hasClass("icon-only-btn")) {
                $(that).text(text == t("Follow") ? t("Request Sent") : t("Follow"));
              }
            }
            if (what === "follow") {
              if (!$(that).hasClass("icon-only-btn")) {
                $(that).text(text == t("Follow") ? t("Following") : t("Follow"));
              }
              $(that).toggleClass("primary-btn");
              $(that).toggleClass("grey-btn");
            }
          } else {
            newAlert(message);
          }
        },
        error: function (data) {
          console.log("error creating activity");
          console.log(data);
        }
      });
    }
  });
  $("[data-remove]").click(function () {
    var what = $(this).attr("data-remove");
    var content = $(this).attr("data-id");
    var that = this;
    var confirmed;
    if (what === "follow" && $(this).hasClass("active-follow") || $(this).hasClass("unfollow-opt")) {
      confirmed = confirm(t("confirm-remove-follow"));
    } else {
      confirmed = true;
    }
    if (confirmed === true) {
      $.ajax({
        type: "POST",
        url: "/api/" + version + "/ractivity",
        dataType: "json",
        data: {
          what: what,
          content: content
        },
        success: function (response) {
          var status = response.status;
          var message = response.message;
          if (status === "success") {
            console.log(message);
          } else {
            newAlert(message);
          }
        },
        error: function (data) {
          console.log("error removing activity");
        }
      });
    }
  });
  $("[data-blacklist]").click(function () {
    var type = $(this).attr("data-blacklist");
    var target = $(this).attr("data-target");
    var confirmed = confirm("Are you sure?");
    if (confirmed === true) {
      $.ajax({
        type: "POST",
        url: "/api/" + version + "/blacklist",
        dataType: "json",
        data: {
          type: type,
          target: target
        },
        success: function (response) {
          var status = response.status;
          var message = response.message;
          if (status === "success") {
            newAlert(message);
          } else {
            newAlert(t("general-error-message"));
          }
        },
        error: function (data) {
          console.log("error creating activity");
        }
      });
    }
  });
  // Dropdowns
  $("[data-dropdown]").on('click', function (e) {
    $(".dropdown").fadeOut();
    var that = this;
    setTimeout(function () {
      var id = $(that).attr("data-dropdown");
      var item = $(that).attr("data-item");
      $(".dropdown#" + id).fadeIn();
      $(".dropdowns-container").css("display", "flex");
      if (id === "post-menu" || id === "convo-menu" || id === "profile-menu" || id === "block-menu") {
        id = id.replace("-menu", "");
        $("." + id + "-opts-container").html("<div class='load white'></div>");
        $("." + id + "-opts-container").addClass("inline-loading");
        $.ajax({
          type: "POST",
          url: "/backend",
          data: {
            action: "menus",
            id: item,
            type: id
          },
          success: function (response) {
            $("." + id + "-opts-container").removeClass("inline-loading");
            $("." + id + "-opts-container").html(response);
            activate();
          },
          error: function (data) {
            newAlert("Whoops! Error loading options.");
          }
        });
      }
    }, 500);
  });
  $("[data-copy]").click(function () {
    copyText($(this).attr("data-copy"));
  });
  $(".dropdowns-container").on('click', function (e) {
    $(".dropdown").slideUp();
    $(this).fadeOut();
  });
  $(".delete-post").click(function () {
    modals("delete-post");
    delete_id = $(this).attr("data-delete");
  });
  $(".cancel-post-btn").click(function () {
    $(".modal#new-post h2").html(t("New Post"));
    reply_id = false;
    repost_id = false;
    $(".ref-post").html("");
  });
  $(".reply-btn").click(function () {
    var id = $(this).attr("data-id");
    var thread = $(this).attr("data-thread");
    reply_id = id;
    repost_id = false;
    if (thread) thread_id = thread;
    $(".modal#new-post h2").html(t("Reply to..."));
    $(".ref-post").html("<div class='load'></div>");
    $(".ref-post").addClass("inline-loading");
    modals("new-post");
    if (thread_id) {
      $(".post-privacy").hide();
      $(".reply-privacy").show();
    } else {
      $(".post-privacy").show();
      $(".reply-privacy").hide();
    }
    $.ajax({
      type: "POST",
      url: "/backend",
      data: {
        action: "getPost",
        id: id
      },
      success: function (response) {
        $(".ref-post").removeClass("inline-loading");
        $(".ref-post").html(response);
        lazyLoad();
      },
      error: function (data) {
        modals("new-post");
        newAlert("Error loading the post you wanted to reply to.");
      }
    });
  });
  $(".repost-btn").click(function () {
    var id = $(this).attr("data-id");
    repost_id = id;
    reply_id = false;
    $(".modal#new-post h2").html(t("Repost to your profile"));
    $(".ref-post").html("<div class='load'></div>");
    $(".ref-post").addClass("inline-loading");
    modals("new-post");
    $(".post-privacy").show();
    $(".reply-privacy").hide();
    $.ajax({
      type: "POST",
      url: "/backend",
      data: {
        action: "getPost",
        id: id
      },
      success: function (response) {
        $(".ref-post").removeClass("inline-loading");
        $(".ref-post").html(response);
        lazyLoad();
      },
      error: function (data) {
        modals("new-post");
        newAlert("Error loading the post you wanted to repost.");
      }
    });
  });
  $(".message-btn").click(function () {

  });
  $(".accept-btn, .decline-btn").click(function () {
    var id = $(this).attr("data-id");
    var allow = $(this).attr("data-allow");
    $.ajax({
      type: "POST",
      url: "/api/" + version + "/remove",
      dataType: "json",
      data: {
        id: id,
        allow: allow
      },
      success: function (response) {
        if (response.message) newAlert(response.message);
        setTimeout(function () {
          load("activity");
        }, 100);
      },
      error: function (data) {
        newAlert("Error doing stuff with activity.");
      }
    });
  });
  $(".report-opt").click(function () {
    var id = $(this).attr("data-id");
    var type = $(this).attr("data-type");
    var reporter = $(this).attr("data-reporter");
    $.ajax({
      type: "POST",
      url: "/api/" + version + "/report",
      dataType: "json",
      data: {
        id: id,
        type: type,
        reporter: reporter
      },
      success: function (response) {
        if (response.message) newAlert(response.message);
        newAlert("Here are some options to limit your interactions with the author of this content.");
        $(".block-opt").click();
      },
      error: function (data) {
        newAlert("Error reporting content. Please contact our moderation team directly at mods@pengin.app.");
      }
    });
  });
  $(".edit-post").click(function () {
    $("#edit-title").val("");
    $("#edit-content").val("");
    modals("edit-post");
    var id = $(this).attr("data-edit");
    $(".edit-container").html("<div class='load white'></div>");
    $(".edit-container").addClass("inline-loading");
    edit_id = id;
    $.ajax({
      type: "POST",
      url: "/api/" + version + "/edit",
      data: {
        id: id
      },
      success: function (response) {
        var message = response.message;
        var title = response.title;
        var content = response.content;
        if (response.content) {
          $("#edit-content").val(content);
          if (response.title) {
            $("#edit-title").show();
            $("#edit-title").val(title);
          } else {
            $("#edit-title").hide();
          }
        } else {
          setTimeout(function () {
            modals("edit-post");
          }, 100);
          newAlert(message);
        }
      },
      error: function (data) {
        setTimeout(function () {
          modals("edit-post");
        }, 100);
        newAlert("Error loading post for you to edit. Please try again later.");
      }
    });
  });
  $(".pin-post").click(function () {
    var id = $(this).attr("data-id");
    $.ajax({
      type: "POST",
      url: "/api/" + version + "/pin",
      data: {
        id: id
      },
      success: function (response) {
        var status = response.status;
        var message = response.message;
        newAlert(message);
        if (status === "success") {

        }
      },
      error: function (data) {
        modals("edit-post");
        newAlert(t("Error loading post."));
      }
    });
  });
  $("#content").on('click', function () {
    $(".post-btns-group").show();
  });
  $("#new-thread-form").on("submit", function (event) {
    var form = $("form#" + this.id);
    var formdata = form.serialize();
    event.preventDefault();
    $("#newthread-error").fadeOut();
    $("#newthread-error").attr('class', 'message');
    var checkinputs = checkInputs(this.id);
    var checklimits = checkCount(this.id);
    if (checkinputs === true && checklimits === true) {
      $(":input, :button").prop('disabled', true);
      $.ajax({
        type: "POST",
        url: "/api/" + version + "/newThread",
        data: formdata,
        success: function (response) {
          var status = response.status;
          var message = response.message;
          $(":input, :button").prop('disabled', false);
          if (status !== "error") {
            window.onbeforeunload = null;
            confirmunload = false;
            load('thread', 'id=' + response.id, '/thread/' + response.id);
          } else {
            $("#newthread-error").addClass("error");
            $("#newthread-error").html(message);
            $("#newthread-error").fadeIn();
          }
        },
        error: function (data) {
          $(":input, :button").prop('disabled', false);
          $("#newthread-error").addClass("error");
          $("#newthread-error").html("Oops! There was an error creating a thread, please try again later.");
          $("#newthread-error").fadeIn();
        }
      });
    } else { // do nothing, the error messages are handled by another function
      console.log("Required inputs are empty or an input is beyond it's character limit");
    }
    event.preventDefault();
  });
  $(".save-btn").on("click", function (event) {
    $("#user-settings-form").submit();
  });
  $("#user-settings-form, #privacy-settings-form").on("submit", function (event) {
    var form = $("form#" + this.id);
    var formdata = form.serialize();
    event.preventDefault();
    $("#settings-message").fadeOut();
    $("#settings-message").attr('class', 'message');
    var checkinputs = checkInputs(this.id);
    var checklimits = checkCount(this.id);
    if (checkinputs === true && checklimits === true) {
      $(":input, :button").prop('disabled', true);
      $.ajax({
        type: "POST",
        url: "/accounts/auth",
        data: formdata,
        success: function (response) {
          $(":input, :button").prop('disabled', false);
          if (response.includes("updated")) {
            $("#updated").html(response);
            $("#settings-message").addClass("success");
            $("#settings-message").html("Account changes saved!");
            $("#settings-message").fadeIn();
            setTimeout(function () {
              $("#settings-message").fadeOut();
            }, 3000);
          } else {
            $("#settings-message").addClass("error");
            $("#settings-message").html(response);
            $("#settings-message").fadeIn();
          }
          $("#settings-message").addClass("fixed-message");
        },
        error: function (data) {
          $(":input, :button").prop('disabled', false);
          $("#settings-message").addClass("error");
          $("#settings-message").html("Oops! There was an error saving your changes, please try again later.");
          $("#settings-message").fadeIn();
        }
      });
    } else { // do nothing, the error messages are handled by another function
      console.log("Required inputs are empty or an input is beyond it's character limit");
    }
    event.preventDefault();
  });
  $("#reset-password-form").on("submit", function (event) {
    event.preventDefault();
    $("#reset-error").fadeOut();
    var checkinputs = checkInputs(this.id);
    var checklimits = checkCount(this.id);
    var form = $("form#" + this.id);
    var formdata = form.serialize();
    if (checkinputs === true && checklimits === true) {
      $(":input, :button").prop('disabled', true);
      $.ajax({
        type: "POST",
        url: "/accounts/auth",
        data: formdata,
        success: function (response) {
          $(":input, :button").prop('disabled', false);
          $("iframe").attr("src", $("iframe").attr("src"));
          if (response === "sent") {
            $("#email").val("");
            $("#reset-success").html(response);
            $("#reset-success").fadeIn();
          } else {
            $("#reset-error").html(response);
            $("#reset-error").fadeIn();
          }
        },
        error: function (data) {
          $(":input, :button").prop('disabled', false);
          $("#reset-error").html("Backend error, please try again later.");
          $("#reset-error").fadeIn();
        }
      });
    } else { // do nothing, the error messages are handled by another function
      console.log("Required inputs are empty or an input is beyond it's character limit");
      $(":input, :button").prop('disabled', false);
    }
    event.preventDefault();
  });
  $("#change-password-form").on("submit", function (event) {
    event.preventDefault();
    $("#password-error").fadeOut();
    var checkinputs = checkInputs(this.id);
    var checklimits = checkCount(this.id);
    var form = $("form#" + this.id);
    var formdata = form.serialize();
    if (checkinputs === true && checklimits === true) {
      $(":input, :button").prop('disabled', true);
      $.ajax({
        type: "POST",
        url: "/accounts/auth",
        data: formdata,
        success: function (response) {
          $(":input, :button").prop('disabled', false);
          if (response === "reset") {
            newAlert("Your password has been changed.");
            setInterval(function () {
              window.location = "/settings/account";
            }, 3000);
          } else {
            $("#password-error").html(response);
            $("#password-error").fadeIn();
          }
        },
        error: function (data) {
          $(":input, :button").prop('disabled', false);
          $("#password-error").html("Backend error updating your account.");
          $("#password-error").fadeIn();
        }
      });
    } else { // do nothing, the error messages are handled by another function
      console.log("Required inputs are empty or an input is beyond it's character limit");
      $(":input, :button").prop('disabled', false);
    }
    event.preventDefault();
  });
  $(".edit-profile").click(function () {
    $("#avatarFile").click();
  });
  $("#avatarFile").change(function () {
    var fd = new FormData();
    var files = $('#avatarFile')[0].files[0];
    fd.append('action', 'avatars');
    fd.append('file', files);
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/img/upload', true);
    $(":input, :button").prop('disabled', true);
    $(".indeterminate").hide();
    $(".determinate").show();
    $(".upload-photo").show();
    xhr.upload.onprogress = function (e) {
      if (e.lengthComputable) {
        var percentComplete = (e.loaded / e.total) * 100;
        $(".progress-text").html("Uploading..." + Math.round(percentComplete) + '%');
        $(".determinate").css('width', Math.round(percentComplete) + '%');
      }
    };
    xhr.onload = function () {
      if (this.status === 200) {
        var response = this.responseText;
        $(":input, :button").prop('disabled', false);
        $(".upload-photo").hide();
        console.log(response);
        if (response === "badext") {
          newAlert("Only .jpeg, .png and .gif files are allowed.");
        } else if (response === "toolarge") {
          newAlert("Your file is too large for us (yourimage > 500 MB)");
        } else if (response.includes("error")) {
          newAlert("We couldn't upload that photo for some reason, please try again later.");
        } else {
          //$(".navigation-profile").css("background-image","url("+this.responseText+")");
          newAlert("Avatar updated! Lookin' good!");
          $("#edit-val-image").val(response);
          saveEdit("image", true);
          setTimeout(function () {
            window.location = window.location.href;
          }, 1000);
        }
        if (response === "badext" || response === "toolarge" || response.includes("error")) {
          activate();
        }
      } else {
        console.log("goofed");
        $(":input, :button").prop('disabled', false);
        $(".upload-photo").hide();
        newAlert("We couldn't upload that photo for some reason, please try again later.");
        //console.log(this.responseText);
      }
    };
    xhr.send(fd);
  });
  $("#message-content").on("input", function () {
    var val = $("#message-content").val();
    if (val) {
      $(".send-msg-btn").prop("disabled", false);
    } else {
      $(".send-msg-btn").prop("disabled", true);
    }
  });
  $(".send-msg-btn").click(function () {
    $(":input, :button").prop("disabled", true);
    $("#newmessage-error").fadeOut();
    var content = $("#message-content").val();
    var convo = $(this).attr("data-id");
    var id = guidGenerator();
    addMessage(id, convo, content);
    if (content) {
      $.ajax({
        type: "POST",
        url: "/api/" + version + "/message",
        dataType: "json",
        data: {
          id: id,
          convo: convo,
          content: content
        },
        success: function (response) {
          var status = response.status;
          var message = response.message;
          $(":button, :input").prop("disabled", false);
          if (status === "success") {
            addMessage(id, convo);
            scrollMessages();
            $("#message-content").val("");
          } else {
            $("#newmessage-error").html(message);
            $("#newmessage-error").fadeIn();
          }
        },
        error: function (data) {
          console.log("error creating new convo");
          $("#newmessage-error").html("There was an error creating a new conversation.");
          $("#newmessage-error").fadeIn();
        }
      });
    }
  });
  if (action === "messages") {
    $("body").addClass("hinder");
    if (ismobile) $(".new-post").fadeOut();
    loadConversation(false, true);
    setInterval(function () {
      loadConversation();
    }, 2500);
  }
  if (!$(".posts-container").html()) {
    if (action === "home") content(".posts-container", "feed", "type=home&weeks=1");
  }
  if (!$(".activity-container").html()) {
    if (action === "activity") content(".activity-container", "activity");
  }
  if (!$(".threads-container").html()) {
    if (action === "created") content(".threads-container", "threads", "type=created");
  }
  if (!$(".threads-container").html()) {
    if (action === "favorites") content(".threads-container", "threads", "type=favorites");
  }
}

function lazyLoad() {
  ! function (window) {
    var $q = function (q, res) {
        if (document.querySelectorAll) {
          res = document.querySelectorAll(q);
        } else {
          var d = document,
            a = d.styleSheets[0] || d.createStyleSheet();
          a.addRule(q, 'f:b');
          for (var l = d.all, b = 0, c = [], f = l.length; b < f; b++)
            l[b].currentStyle.f && c.push(l[b]);

          a.removeRule(0);
          res = c;
        }
        return res;
      },
      addEventListener = function (evt, fn) {
        window.addEventListener
          ? this.addEventListener(evt, fn, false)
          : (window.attachEvent)
          ? this.attachEvent('on' + evt, fn)
          : this['on' + evt] = fn;
      },
      _has = function (obj, key) {
        return Object.prototype.hasOwnProperty.call(obj, key);
      };

    function loadImage(el, fn) {
      if (el.getAttribute('data-src')) {
        var img = new Image(),
          src = el.getAttribute('data-src');
        img.onload = function () {
          if (!!el.parent)
            el.parent.replaceChild(img, el)
          else
            el.src = src;

          fn ? fn() : null;
        }
        img.src = src;
      } else {
        var img = new Image(),
          src = el.getAttribute('data-background');
        img.onload = function () {
          if (!!el.parent)
            el.parent.replaceChild(img, el)
          else
            //el.src = src;
            $(el).css("background-image", "url(" + src + ")");
          fn ? fn() : null;
        }
        img.src = src;
      }
    }

    function elementInViewport(el) {
      var rect = el.getBoundingClientRect()

      return (
        rect.top >= 0
        && rect.left >= 0
        && rect.top <= (window.innerHeight || document.documentElement.clientHeight)
      )
    }

    var images = new Array(),
      query = $q('[data-src],[data-background]'),
      processScroll = function () {
        for (var i = 0; i < images.length; i++) {
          if (elementInViewport(images[i])) {
            loadImage(images[i], function () {
              images.splice(i, i);
            });
          }
        };
      };
    // Array.prototype.slice.call is not callable under our lovely IE8
    for (var i = 0; i < query.length; i++) {
      images.push(query[i]);
    };

    processScroll();
    addEventListener('scroll', processScroll);

  }(this);
}

// Localization
var request = new XMLHttpRequest();
request.open("GET", "/translations/" + lang + ".json", false);
request.send(null);
var localize = JSON.parse(request.responseText);

function t(text) {
  if (localize[text]) {
    return localize[text];
  } else return text;
}

var edit_id;
var delete_id;

function deletePost() {
  $.ajax({
    type: "POST",
    url: "/api/" + version + "/deletePost",
    dataType: "json",
    data: {
      id: delete_id
    },
    success: function (response) {
      var status = response.status;
      var message = response.message;
      newAlert(message);
      if (status === "success") {
        $(".post#post-" + delete_id).slideUp();
        setTimeout(function () {
          $("#post-" + delete_id).remove();
        }, 1000);
      }
    },
    error: function (data) {
      newAlert("Error deleting post.");
    }
  });
}

function savePost() {
  var content = $("#edit-content").val();
  var title = $("#edit-title").val();
  $.ajax({
    type: "POST",
    url: "/api/" + version + "/updatePost",
    dataType: "json",
    data: {
      id: edit_id,
      title: title,
      content: content
    },
    success: function (response) {
      var status = response.status;
      var message = response.message;
      newAlert(message);
      if (status === "success") {
        window.location = window.location.href;
      }
    },
    error: function (data) {
      newAlert("Error removing post.");
    }
  });
}

function copyText(text) {
  var $temp = $("<input/>");
  $("body").append($temp);
  $temp.val(text).select();
  document.execCommand("copy");
  $temp.remove();
  newAlert("Copied to clipboard!");
}

// Cookies
function getCookie(c_name) {
  var c_value = document.cookie;
  var c_start = c_value.indexOf(" " + c_name + "=");
  if (c_start == -1) {
    c_start = c_value.indexOf(c_name + "=");
  }
  if (c_start == -1) {
    c_value = null;
  } else {
    c_start = c_value.indexOf("=", c_start) + 1;
    var c_end = c_value.indexOf(";", c_start);
    if (c_end == -1) {
      c_end = c_value.length;
    }
    c_value = unescape(c_value.substring(c_start, c_end));
  }
  return c_value;
}

function setCookie(c_name, value, exdays) {
  var exdate = new Date();
  exdate.setDate(exdate.getDate() + exdays);
  var c_value = escape(value) + ((exdays === null) ? "" : "; expires=" + exdate.toUTCString()) + ";path=/";
  document.cookie = c_name + "=" + c_value;
}

function eraseCookie(name) {
  document.cookie = name + '=; Max-Age=0';
}

function showMessage(id, toggle = true, newtext = false, type = false, fade = true, timeout = 3000) {
  if (id) {
    var element = ".message#" + id;
    if (timeout !== 3000) {
      timeout = timeout + '000';
    }
    if (type !== false) {
      $(element).attr("class", "message");
      $(element).addClass(type);
    }
    if (newtext !== false) {
      $(element).html(newtext);
    }
    if (toggle === true) {
      $(element).fadeIn();
    } else {
      $(element).fadeOut();
    }
    if (fade !== false) {
      setTimeout(function () {
        $(element).fadeOut();
      }, timeout);
    }
  }
}

function confirmLogout() {
  var r = confirm("Are you sure you want to log out?");
  if (r === true) window.location.href = '/accounts/logout';
}

function checkInputs(id) {
  var filled;
  var extra;
  if (id === "submission-form") {
    var extra = "<p></p>";
  }
  $("form#" + id + " input, form#" + id + " textarea").each(function () {
    if ($(this).attr('data-require') == 'true' && !$(this).val()) {
      console.log("false");
      $(this).addClass("require-input");
      if (!$("#error-" + this.id).length) {
        if (extra) {
          $(this).after(extra + "<br/><div class='require-error' id='error-" + this.id + "'>This field is required</div>");
        } else {
          $(this).after("<br/><div class='require-error' id='error-" + this.id + "'>This field is required</div>");
        }
      } else {
        $("#error-" + this.id).fadeIn();
      }
      filled = "false";
    } else {
      console.log("true");
      $(this).removeClass("require-input");
      $("#error-" + this.id).fadeOut();
      if (filled !== "false") {
        filled = "true";
      }
    }
    ++field_count;
  });
  console.log("all required inputs are filled: " + filled);
  if (filled === "true") {
    return true;
  } else {
    return false;
  }
}

function removeValue(list, value) {
  return list.replace(new RegExp(",?" + value + ",?"), function (match) {
    var first_comma = match.charAt(0) === ',',
      second_comma;

    if (first_comma
      && (second_comma = match.charAt(match.length - 1) === ',')) {
      return ',';
    }
    return '';
  });
}

function checkCount(id) {
  var limited;
  $("form#" + id + " input, form#" + id + " textarea").each(function () {
    if ($(this).attr('data-limit') < $(this).val().length) {
      var this_limit = $(this).attr('data-limit');
      $(this).addClass("require-input");
      if (!$("#error-limit" + this.id).length) {
        $(this).after("<br/><div class='require-error' id='error-limit" + this.id + "'>Field must be under " + this_limit + " characters</div>");
      } else {
        $("#error-limit" + this.id).fadeIn();
      }
      limited = "false";
    } else {
      $(this).removeClass("require-input");
      $("#error-limit" + this.id).fadeOut();
      if (limited !== "false") {
        limited = "true";
      }
    } // console.log("all inputs are under their character limits: " + limited);
  });
  if (limited === "true") {
    return true;
  } else {
    return false;
  }
}

function getValue(varname) {
  var url = window.location.href.replace(new RegExp("\\+", "g"), "%20");
  var qparts = url.split("?");
  if (qparts.length == 0) {
    return "";
  }
  var query = qparts[1];
  var vars = query.split("&");
  var value = "";
  for (i = 0; i < vars.length; i++) {
    var parts = vars[i].split("=");
    if (parts[0] == varname) {
      value = parts[1];
      break;
    }
  }
  value = unescape(value);
  value.replace("+", " ");
  return value;
}

function scrollOn(id, add = 0, sub = 0) {
  if (id.includes(".") === false) {
    var id = "#" + id;
  }
  $('html,body').animate({
    scrollTop: $(id).offset().top + add - sub
  });
}

function guidGenerator() {
  var S4 = function () {
    return (((1 + Math.random()) * 0x10000) | 0).toString(16).substring(1);
  };
  return (S4() + S4() + "-" + S4() + "-" + S4() + "-" + S4() + "-" + S4() + S4() + S4());
}

function goTo(url, newtab) {
  if (newtab) {
    var win = window.open(url, '_blank');
    win.focus();
  } else {
    window.location = url;
  }
}

function newAlert(text) {
  var id = guidGenerator();
  $(".alert-feed").append("<div><div class='alert' id='alert-" + id + "'>" + text + "</div></div>");
  setTimeout(function () {
    $(".alert#alert-" + id).slideDown();
    setTimeout(function () {
      $(".alert#alert-" + id).addClass("active-alert");
    }, 1000);
    setTimeout(function () {
      $(".alert#alert-" + id).removeClass("active-alert");
      $(".alert#alert-" + id).slideUp();
      setTimeout(function () {
        $(".alert#alert-" + id).remove();
      }, 500);
    }, 5000);
  }, 100);
}

function colEdit(col, other) {
  $(".dashboard-navigation").addClass("disable-this");
  if (other) {
    $("#edit-val-" + col).show();
  }
  $("#column-value-" + col).removeClass("save-flash");
  $("#column-" + col).addClass("active-column");
  $(".edit-column-container:not(.active-column)").addClass("inactive-column");
  $("#edit-btn-" + col).hide();
  $("#context-" + col).show();
  $("#save-btn-" + col).show();
  $("#cancel-btn-" + col).show();
  $(".edit-column-change#change-" + col).show();
  $("#column-value-" + col).hide();
  $("#edit-val-" + col).focus();
  $(".edit-column-btn").prop('disabled', true);
}

function columnError(text, col) {
  $("#edit-val-" + col).addClass("require-input");
  if ($("#error-" + col).is(":visible")) {
    $("#error-" + col).html(text);
    $("#error-" + col).fadeIn();
  } else {
    $("#edit-val-" + col).after("<p class='require-error' id='error-" + col + "'>" + text + "</p>");
  }
}

function saveEdit(col, other) {
  $("#error-" + col).fadeOut();
  var value = $("#edit-val-" + col).val();
  if (!value && (col === "displayname" || col === "username" || col === "email")) {
    columnError("This field is required.", col);
  } else {
    $(":button, :input").prop('disabled', true);
    $.ajax({
      type: "POST",
      url: "/accounts/auth",
      data: {
        action: "save",
        column: col,
        value: value
      },
      success: function (response) {
        console.log(response);
        if (response === "taken") {
          columnError("Sorry, someone beat you to that one!", col);
          $(":button, :input").prop('disabled', false);
        } else if (response.includes("error") === false) {
          cancelEdit(col, other);
          value = response;
          if (value) {
            if (col === "user_name") {
              $("#column-value-" + col).html("<a href='/page/" + value + "' target='_blank'>whatzup.com/page/" + value + "</a>");
              $("#pagelink").attr('href', '/page/' + value);
            } else if (col === "hours") {
              load('account');
              newAlert("Wham-o! Settings saved.");
            } else {
              if (col === "extraurl") {
                value = "<a href='" + response + "' target='_blank' rel='noopener'>" + response + "</a>";
              }
              $("#column-value-" + col).html(value);
            }
            if (col === "performer") {
              $(".fade-title").html(value);
            } else if (col === "user_name") {
              $(".fade-username").html("@" + value);
            }
          } else {
            $("#column-value-" + col).html("<span class='fade'>Nothing here yet...</span>");
          }
          $("#edit-val-" + col).addClass("save-flash");
          $(":button, :input").prop('disabled', false);
        } else {
          columnError(response, col);
          $(":button, :input").prop('disabled', false);
        }
      },
      error: function (data) {
        $(".dashboard-navigation").removeClass("disable-this");
        console.log(data);
        cancelEdit(col);
        $("#column-value-" + col).html("<span style='color: #de2233;'>Backend error saving, please contact us by going to the help & support tab</span>");
        $(":button, :input").not('#hours-container input').prop('disabled', false);
      }
    });
  }
}

function cancelEdit(col) {
  $(".require-error").fadeOut();
  var original = $("#column-value-" + col).html();
  $("#edit-val-" + col).removeClass("require");
  $(".edit-column-btn").prop('disabled', false);
  $(".edit-column-container").removeClass("inactive-column");
  $("#column-" + col).removeClass("active-column");
  $(".dashboard-navigation").removeClass("disable-this");
}

function userSearch() {
  $("#usersearch-error").fadeOut();
  var query = $("#user-search").val();
  $.ajax({
    type: "POST",
    url: "/backend",
    data: {
      action: "list",
      type: "messages",
      query: query
    },
    success: function (response) {
      $(".user-list").html(response);
      $(".user-item").click(function () {

      });
      $("#user-search").on('change', function (e) {
        userSearch();
      });
      $(".add-user-btn").on('click', function (e) {
        var id = $(this).attr("data-id");
        var val = $("#convo-users").val();
        var list;
        if (val) {
          if (val.includes(id)) {
            list = removeValue(val, id);
          } else {
            list = val + "," + id;
          }
        } else {
          list = id;
        }
        $("#convo-users").val(list);
        var text = $(this).text();
        if ($(this).hasClass("grey-btn")) {
          $(this).text(text == "Invite" ? "Added" : "Invite");
        } else {
          $(this).text(text == "Add" ? "Added" : "Add");
        }
        $(this).toggleClass("active-add");
        if ($("#convo-users").val()) {
          $(".new-convo-btn").prop("disabled", false);
        } else {
          $(".new-convo-btn").prop("disabled", true);
        }
      });
      lazyLoad();
      activate();
    },
    error: function (data) {
      $("#usersearch-error").fadeIn();
    }
  });
}

function loadConversation(id, scroll) {
  if (id) {
    current_convo = id;
  } else {
    id = current_convo;
  }
  $.ajax({
    type: "POST",
    url: "/backend",
    data: {
      action: "messages",
      id: id
    },
    success: function (response) {
      if (response) {
        $(".messages-container").html(response);
        activate();
        if (scroll) {
          scrollMessages();
        }
      }
    },
    error: function (data) {
      console.log("error loading messages");
    }
  });
}
