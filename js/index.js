$(function(){

  // ----------------------------------------------------
  // カウント変数を定義
  // ----------------------------------------------------

  let myCount = 7;
  let opponentCount = 7;
  let judgeCount = myCount - opponentCount
  let displayJudgeCount = '同点'
  let setId = 1
  let setResultId = 0
  let setResults = []
  let totalMyCount = 0;
  let totalOpponentCount = 0;
  let totalSetResult = 0;

  // ----------------------------------------------------
  // タイマー変数を定義
  // ----------------------------------------------------

  let time = 180;
  let minute = Math.floor(time / 60)
  let seconds = (time % 60).toString().padStart(2, '0')
  let gameTime = minute + ':' + seconds
  let start = 1
  let timer

  // ----------------------------------------------------
  // 名称変数を定義
  // ----------------------------------------------------  

  let tournamentName
  let myTeamName
  let opponentTeamName
  let names = {tournamentName : '', myTeamName : '', opponentTeamName : ''};

  // ----------------------------------------------------
  // 初期表示
  // ----------------------------------------------------

  $(document).ready(function(){
    $('#gameTimer').html(gameTime)
    $('#displayMyCount').html(myCount)
    $('#displayOpponentCount').html(opponentCount)
    $('#displayJudgeCount').html(displayJudgeCount)
    $('#timerStart').html('スタート')
  })


  // ----------------------------------------------------
  // タイマー表示
  // ----------------------------------------------------

  const timerStart = () => {
    if(time > 0  && time <= 180){
      time -= 1
    }

    minute = Math.floor(time / 60)
    seconds = (time % 60).toString().padStart(2, '0')
    gameTime = minute + ':' + seconds
    $('#gameTimer').html(gameTime)
  }

  const timerReset = () => {
    time = 180
    minute = Math.floor(time / 60)
    seconds = (time % 60).toString().padStart(2, '0')
    gameTime = minute + ':' + seconds
    $('#gameTimer').html(gameTime)
  }


  $('#timerStart').on('click', function(){
    
    if(start % 2 === 1){
      timer = setInterval(timerStart, 1000)
      $('#timerStart').html('ストップ')
      $('#timerStart').removeClass('timerStart')
    }
    else if(start % 2 === 0){
      clearInterval(timer)
      $('#timerStart').html('スタート')
      $('#timerStart').addClass('timerStart')
    }

    start += 1
  })

  $('#timerReset').on('click', function(){
    timerReset()
    clearInterval(timer)
    $('#timerStart').html('スタート')
  })


  // ----------------------------------------------------
  // 戦況を表示
  // ----------------------------------------------------

  const judge = (myCount, opponentCount) => {
    judgeCount = myCount - opponentCount
    if(judgeCount > 0){
      displayJudgeCount = judgeCount + '勝ち'
    }else if(judgeCount === 0){
      displayJudgeCount = '同点'
    }else if(judgeCount < 0){
      displayJudgeCount = judgeCount * -1 + '負け'
    }

    $('#displayJudgeCount').html(displayJudgeCount)
  }

  // ----------------------------------------------------
  // クリック時に実行
  // ----------------------------------------------------

  $('#myCountPlus').on('click', function(){
    if(myCount > 0 && myCount < 7){
      myCount += 1;
    }else if(myCount === 0){
      myCount += 1;
      opponentCount = 7;
    }
    $('#displayMyCount').html(myCount)
    $('#displayOpponentCount').html(opponentCount)
    judge(myCount, opponentCount)
  })

  $('#myCountMinus').on('click', function(){
    if(myCount > 0 && myCount <= 7){
      myCount -= 1;
    }else if(myCount === 10){
      myCount = 7;
      opponentCount += 1;
    }
  
    if(myCount === 0){
      opponentCount = 10;
    }

    $('#displayMyCount').html(myCount)
    $('#displayOpponentCount').html(opponentCount)
    judge(myCount, opponentCount)
    
  })

  $('#opponentCountPlus').on('click', function(){
    if(opponentCount > 0 && opponentCount < 7){
      opponentCount += 1;
    }else if(opponentCount === 0){
      opponentCount += 1;
      myCount = 7;
    }
    $('#displayMyCount').html(myCount)
    $('#displayOpponentCount').html(opponentCount)
    judge(myCount, opponentCount)
  })

  $('#opponentCountMinus').on('click', function(){
    if(opponentCount > 0 && opponentCount <= 7){
      opponentCount -= 1;
    }else if(opponentCount === 10){
      opponentCount = 7;
      myCount += 1;
    }
    
    if(opponentCount === 0){
      myCount = 10;
    }

    $('#displayMyCount').html(myCount)
    $('#displayOpponentCount').html(opponentCount)
    judge(myCount, opponentCount)
  })

  // ----------------------------------------------------
  // セットの結果を入力
  // ----------------------------------------------------  

  const setResult = () => {

    if(setId <= 3){
      if(myCount - opponentCount > 0){
        setResultId = 1        
      }else if(myCount - opponentCount === 0){
        setResultId = 0
      }else if(myCount - opponentCount < 1){
        setResultId = -1
      }

      setResults.push({
        setId : setId,
        setResultId : setResultId,
        myCount : myCount,
        opponentCount : opponentCount,
      })

      setId += 1;
      

    }else if(setId === 5){
      setResults.push({
        setId : setId,
        myCount : 'VT',
        opponentCount : 'VT',
      }) 
    }  
  }

  // ----------------------------------------------------
  // セットの結果を表示
  // ----------------------------------------------------  

  const displaySetResult = () => {
    let setResultSelector = '';
    totalMyCount = 0;
    totalOpponentCount = 0;
    totalSetResult = 0;
    
    $.each(setResults, function(index, value){
      if(value.setId <= 3){
        totalMyCount += value.myCount;
        totalOpponentCount += value.opponentCount;
        totalSetResult += value.setResultId
      }

      if(value.setId === 1){
        setResultSelector = '#firstSetResult';
      }else if(value.setId === 2){
        setResultSelector = '#secondSetResult';
      }else if(value.setId === 3){
        setResultSelector = '#thirdSetResult';
      }else if(value.setId === 5){
        setResultSelector = '#victoryThrowResult';
        $(setResultSelector).removeClass('close');
      }

      $(setResultSelector).children('.myResult').html(value.myCount)
      $(setResultSelector).children('.myResultVal').val(value.myCount)
      $(setResultSelector).children('.opponentResult').html(value.opponentCount)
    })

      $('#totalMyResult').html(totalMyCount);
      $('#totalOpponentResult').html(totalOpponentCount);

  }

  // ----------------------------------------------------
  // 大会名・チーム名を配列に追加
  // ----------------------------------------------------

    $('#tournamentName').on('change', function(){
      names['tournamentName'] = $('#tournamentName').val()
    })

    $('#myTeamName').on('change', function(){
      names['myTeamName'] = $('#myTeamName').val()
    })

    $('#opponentTeamName').on('change', function(){
      names['opponentTeamName'] = $('#opponentTeamName').val()
    })

  // ----------------------------------------------------
  // 次へすすむ
  // ----------------------------------------------------

  const next = () => {
    if(setId === 3){
      if(totalSetResult === 2){
        $('#gameFinish').removeClass('close');
        $('#nextSet').addClass('close')
      }else if(totalSetResult === -2){
        $('#gameFinish').removeClass('close');
        $('#nextSet').addClass('close')
      }
    }else if(setId === 4){
      if(totalSetResult > 0 && totalSetResult <= 2){
        $('#gameFinish').removeClass('close');
        $('#nextSet').addClass('close')
      }else if(totalSetResult < 0 && totalSetResult >= -2){
        $('#gameFinish').removeClass('close');
        $('#nextSet').addClass('close')
      }
      else if(totalSetResult === 0){
        if(totalMyCount > totalOpponentCount){  
          $('#gameFinish').removeClass('close');
          $('#nextSet').addClass('close')
        }else if(totalMyCount === totalOpponentCount){
          nextSet = 'VICTORY THROW'
          setId = 5
        }else if(totalMyCount < totalOpponentCount){  
          $('#gameFinish').removeClass('close');
          $('#nextSet').addClass('close')
        }
      }
    }else if(setId === 5){
      $('#gameFinish').removeClass('close');
      $('#nextSet').addClass('close')
    }

  }

  // ----------------------------------------------------
  // カウントリセット関数
  // ----------------------------------------------------
  
  const countReset = () => {
    myCount = 7;
    opponentCount = 7;
    displayJudgeCount = '同点';
    $('#displayMyCount').html(myCount);
    $('#displayOpponentCount').html(opponentCount);
    $('#displayJudgeCount').html(displayJudgeCount)
  }

  // ----------------------------------------------------
  // 次のセットへ
  // ----------------------------------------------------

  $('#nextSet').on('click', function(){
    setResult();
    countReset();
    displaySetResult();
    next()
    $('#setResults').val(JSON.stringify(setResults))
    $('#names').val(JSON.stringify(names))
  })

})