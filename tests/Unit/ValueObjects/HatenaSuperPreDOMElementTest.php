<?php

use Toarupg0318\HatenaBlogClient\ValueObjects\DOM\HatenaSuperPreDOMElement;

it('test HatenaSuperPreDOMElement', function () {
    $value = <<<HATENA
  #!/usr/bin/perl -w
  use strict;
  print <<END;
  <html><body>
    <h1>Hello! World.</h1>
  </body></html>
  END
HATENA;
    $hatenaSuperPreDOMElement = new HatenaSuperPreDOMElement($value);
    expect(strval($hatenaSuperPreDOMElement))
        ->toBe(
            <<<HATENA
&gt;||
  #!/usr/bin/perl -w
  use strict;
  print &lt;&lt;END;
  &lt;html&gt;&lt;body&gt;
    &lt;h1&gt;Hello! World.&lt;/h1&gt;
  &lt;/body&gt;&lt;/html&gt;
  END
||&lt;
HATENA
        );
});