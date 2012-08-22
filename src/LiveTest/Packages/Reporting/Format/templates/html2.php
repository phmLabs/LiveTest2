<?php

/*
 * This file is part of the LiveTest package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
  include_once 'html_functions.php';
?><!DOCTYPE html>
<html>
<head>
  <title>LiveTest | Html Report v2</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <style>
    <!--
      #compactToggleButton{display:table-cell;height:20px;width:150px;}
      #compactToggleButton,.colorLegend{text-align:center;}
      #copyright{padding:20px 0 0 10px;}
      #legend_table{padding-bottom:0;}
      .legend{font-weight:700;padding-right:20px;text-align:right;vertical-align:top;}
      .compact .test_label{padding:0 5px;}
      .result_failed,.result_error,.result_success,.url_column a{color:#fff;}
      .result_none,#compactToggleButton{background-color:#ccc;}
      .standard .testLabelTitleVertical,.compact .testLabelTitleExtended{display:none;}
      .testLabelTitleVertical{overflow:hidden;width:20px;}
      .test_label{padding:0 10px;}
      .url_column,.result_column{padding:5px;}
      .url_error,.result_error{background-color:#3038f2;}
      .url_failed,.result_failed{background-color:#f24028;}
      .url_success,.result_success{background-color:#055902;}
      body{font-family:verdana;font-size:10px;}
      body,a{color:#666;}
      table{font-size:10px;padding-top:20px;}
      table a{text-decoration:none;}
      td,td *,#compactToggleButton{vertical-align:middle;}
    -->
  </style>
  <!--[if lt IE 9]>
  <style type="text/css">
    .testLabelTitleVertical div { margin-left:-5px;}
  </style>
  <![endif]-->
  <script>
    dojoConfig = {
      parseOnLoad: true, //enables declarative chart creation
      gfxRenderer: "svg,vml", // svg has priority, vml is used for IE versions < 9
      canvasEvents:false,
      isDebug: false
    };
  </script>
  <script src="http://ajax.googleapis.com/ajax/libs/dojo/1.6.0/dojo/dojo.xd.js"></script>
  <script>
    var compact = false;
    dojo.require("dojox.gfx");
    String.prototype.width = function (font)
    {
      var el = dojo.create("div",
      {
        id: "invisibeTestDiv",
        'innerHTML': this
      });
      dojo.style(el,
      {
        'position': 'absolute',
        'float': 'left',
        'white-space': 'nowrap',
        'visibility': 'hidden',
        'font': (font || '13px arial')
      });
      var o = dojo.place(el, dojo.body());
      var w = dojo.position(o).w;
      dojo.query(o).orphan();
      return w;
    }

    function createHeadline(dom, text)
    {
      console.log('creating headline: ' + text);
      var surfaceElement = dojo.query(dom).shift();
      var font = {
        family: 'Arial',
        size: "12px",
        weight: "normal"
      };
      var height = text.width(font.weight + ' ' + font.size + ' ' + font.family);
      surface = dojox.gfx.createSurface(surfaceElement, 30, height);
      dojo.query(dom).style(
      {
        'height': height
      });
      var textShape = surface.createTextPath(
      {
        'text': text
      }).moveTo(12, height).vLineTo(0).setFont(
      {
        'family': font.family,
        'weight': font.weight,
        'size': font.size
      }).setFill("black");
    }

    function settings_compactToggle()
    {
      compact = !compact;
      if (compact == true)
      {
        document.getElementById('wrapper').className =  'compact';
        document.getElementById('compactToggleButton').innerHTML = 'Compact View';
      }
      else
      {
        document.getElementById('wrapper').className = 'standard';
        document.getElementById('compactToggleButton').innerHTML = 'Standard View';
      }
    }
    dojo.ready(function ()
    {
      dojo.query(".testLabelTitleVertical").forEach(function (node)
      {
        var text = node.innerHTML;
        node.innerHTML = '';
        dojo.create("div",
        {
          id: text,
          style: 'width:20px;'
        }, node);
        createHeadline('#' + text, text);
      });
      settings_compactToggle();
      //this crashes some versions of firefox if toggled manually. no clue why.
      //document.getElementById('compactToggleButton').addEventListener("click", settings_compactToggle, false);
    });
  </script>
</head>
<body >
  <div id="wrapper" class="standard">
    <table>
      <tr>
        <td class="legend">Display Style:</td>
        <td colspan="3">
        <div id="compactToggleButton">Standard View</div>
      </tr>
      <tr style="height: 10px"><td colspan="4"></td></tr>
      <tr>
        <td class="legend">Run Information</td>
        <td colspan="3">Date: <?php echo date( 'Y-m-d H:i:m'); ?></td>
      </tr>
      <tr>
        <td></td>
        <td colspan="3">Default Domain: <b><?php echo $information->getDefaultDomain(); ?></b></td>
      </tr>
      <tr>
        <td></td>
        <td colspan="3">Duration: <b><?php echo \Base\Date\Duration::format(floor($information->getDuration()/1000), '%d day(s) ', '%d hour(s), ', '%d minute(s) ', '%d second(s)'); ?></b></td>
      </tr>
      <tr>
        <td></td>
        <td colspan="3">Number of Tests: <b><?php echo $testCount; ?></b></td>
      </tr>
      <tr style="height: 10px"><td colspan="4"></td></tr>
      <tr>
        <td class="legend">Legend</td>
        <td style="min-width:100px;" class="result_success result_column colorLegend">Success</td>
        <td style="min-width:100px;" class="result_failed result_column colorLegend">Failure</td>
        <td style="min-width:100px;" class="result_error result_column colorLegend">Error</td>
      </tr>
<?php if( count( $connectionStatuses ) > 0 ): ?>
      <tr style="height: 10px"><td colspan="4"></td></tr>
      <tr>
        <td class="legend">Connection Errors</td>
        <td colspan="3">
          <ul>
<?php foreach ($connectionStatuses as $status ):?>
            <li><a href="<?php echo htmlspecialchars ( $status->getRequest()->getUri() ); ?>"><?php echo htmlentities( $status->getRequest()->getUri()); ?></a></li>
<?php endforeach; ?>
          </ul>
        </td>
      </tr>
<?php endif; ?>
    </table>
    <table>
      <thead>
        <tr>
          <th></th>
<?php foreach ( $tests as $test ): ?>
          <th class="test_label">
            <span class="testLabelTitleVertical"><?php echo $test->getName(); ?></span>
            <span class="testLabelTitleExtended"><?php echo $test->getName(); ?><br/><?php echo $test->getClassName()?></span>
          </th>
<?php endforeach;?>
        </tr>
      </thead>
      <tbody>
<?php foreach ($matrix as $url => $testInfo): $testList = $testInfo['tests']; ?>
        <tr>
          <td class="url_column <?php echo getRowClass( $testInfo['status'] );?>"><a href="<?php echo htmlspecialchars ( $url ) ?>" target="_blank"><?php echo htmlentities($url); ?></a></td>
<?php
  foreach ($tests as $test):
    if( array_key_exists($test->getName(), $testList) ) {
      $content = getHtmlContent( $testList[$test->getName()] );
    }else{
      $content = array( 'css_class'=> 'result_none', 'message' => '');
    }
?>
          <td class="result_column <?php echo $content['css_class'].' '.$test->getName().'Class'; ?>"><?php echo htmlentities($content['message']); ?></td>
<?php endforeach; ?>
        </tr>
<?php endforeach; ?>
      </tbody>
    </table>
    <br/>
    <span id="copyright">Html Report by <b><a href="http://livetest.phmlabs.com">LiveTest</a></b></span>
  </div>
</body>
</html>
