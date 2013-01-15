<?php
/**
 * @copyright	Copyright 2010-2013, The Titon Project
 * @license		http://opensource.org/licenses/bsd-license.php
 * @link		http://titon.io
 */

namespace Titon\Mvc;

use Titon\Mvc\View;
use Titon\Mvc\Helper\Html\HtmlHelper;
use Titon\Mvc\Helper\Html\FormHelper;
use \Exception;

/**
 * Test class for Titon\Mvc\View.
 */
class ViewTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var \Titon\Mvc\View
	 */
	public $object;

	/**
	 * Initialize the view.
	 */
	protected function setUp() {
		$this->object = new View([
			TEMP_DIR,
			TEMP_DIR . '/fallback'
		]);
	}

	/**
	 * Test that path adding and getting works.
	 */
	public function testPaths() {
		$expected = [
			TEMP_DIR,
			TEMP_DIR . '/fallback'
		];
		$this->assertEquals($expected, $this->object->getPaths());

		$this->object->addPath(TEST_DIR);
		$expected[] = TEST_DIR;
		$this->assertEquals($expected, $this->object->getPaths());
	}

	/**
	 * Test that adding and getting helpers work.
	 */
	public function testHelpers() {
		$form = new FormHelper();
		$html = new HtmlHelper();

		$this->assertEquals([], $this->object->getHelpers());

		$this->object->addHelper('html', $html);
		$this->assertEquals([
			'html' => $html
		], $this->object->getHelpers());

		$this->object->addHelper('form', $form);
		$this->assertEquals([
			'html' => $html,
			'form' => $form
		], $this->object->getHelpers());

		$this->assertInstanceOf('Titon\Mvc\Helper', $this->object->getHelper('html'));

		try {
			$this->assertInstanceOf('Titon\Mvc\Helper', $this->object->getHelper('foobar'));
			$this->assertTrue(false);

		} catch (Exception $e) {
			$this->assertTrue(true);
		}
	}

	/**
	 * Test that setting and getting variables work.
	 */
	public function testVariables() {
		$expected = [];
		$this->assertEquals($expected, $this->object->getVariables());

		$this->object->setVariable('foo', 'bar');
		$expected['foo'] = 'bar';
		$this->assertEquals($expected, $this->object->getVariables());

		$this->object->setVariables([
			'numeric' => 1337,
			'boolean' => false
		]);
		$expected['numeric'] = 1337;
		$expected['boolean'] = false;
		$this->assertEquals($expected, $this->object->getVariables());

		$this->assertEquals('bar', $this->object->getVariable('foo'));

		try {
			$this->assertEquals('bar', $this->object->getVariable('fake'));
			$this->assertTrue(false);

		} catch (Exception $e) {
			$this->assertTrue(true);
		}
	}

	/**
	 * Test that locateTemplate() returns an absolute path or throws an exception.
	 */
	public function testLocatePath() {
		$this->assertEquals(TEMP_DIR . '/public/index/add.tpl', $this->object->locateTemplate(['index', 'add']));
		$this->assertEquals(TEMP_DIR . '/public/index/view.xml.tpl', $this->object->locateTemplate(['controller' => 'index', 'action' => 'view', 'ext' => 'xml']));

		// partials
		$this->assertEquals(TEMP_DIR . '/private/includes/include.tpl', $this->object->locateTemplate('include', View::PARTIAL));
		$this->assertEquals(TEMP_DIR . '/private/includes/nested/include.tpl', $this->object->locateTemplate('nested/include', View::PARTIAL));

		// wrapper
		$this->assertEquals(TEMP_DIR . '/private/wrappers/wrapper.tpl', $this->object->locateTemplate('wrapper', View::WRAPPER));
		$this->assertEquals(TEMP_DIR . '/fallback/private/wrappers/fallback.tpl', $this->object->locateTemplate('fallback', View::WRAPPER));

		// layout
		$this->assertEquals(TEMP_DIR . '/private/layouts/default.tpl', $this->object->locateTemplate('default', View::LAYOUT));
		$this->assertEquals(TEMP_DIR . '/fallback/private/layouts/fallback.tpl', $this->object->locateTemplate('fallback', View::LAYOUT));

		// custom
		$this->assertEquals(TEMP_DIR . '/private/errors/404.tpl', $this->object->locateTemplate('404', View::CUSTOM, 'errors'));
		$this->assertEquals(TEMP_DIR . '/fallback/private/emails/example.html.tpl', $this->object->locateTemplate(['example', 'ext' => 'html'], View::CUSTOM, 'emails'));

		// missing
		try {
			$this->assertEquals(TEMP_DIR . '/public/index/missing.tpl', $this->object->locateTemplate(['index', 'missing']));
			$this->assertTrue(false);

		} catch (Exception $e) {
			$this->assertTrue(true);
		}
	}

	/**
	 * Test that render() parses and returns a template.
	 */
	public function testRender() {
		$this->assertEquals('add.tpl', $this->object->render($this->object->locateTemplate(['index', 'add'])));
		$this->assertEquals('test-include.tpl nested/include.tpl', $this->object->render($this->object->locateTemplate(['index', 'test-include'])));

		// variables
		$this->assertEquals('Titon - partial - variables.tpl', $this->object->render($this->object->locateTemplate('variables', View::PARTIAL), [
			'name' => 'Titon',
			'type' => 'partial',
			'filename' => 'variables.tpl'
		]));
	}

	/**
	 * Test that run() renders all templates in the loop.
	 */
	public function testRun() {
		$this->assertEquals('<layout>edit.tpl</layout>', $this->object->run(['index', 'edit']));

		$this->object->getEngine()->useLayout('fallback');
		$this->assertEquals('<fallbackLayout>add.tpl</fallbackLayout>', $this->object->run(['index', 'add']));

		$this->object->getEngine()->wrapWith('wrapper');
		$this->assertEquals('<fallbackLayout><wrapper>index.tpl</wrapper></fallbackLayout>', $this->object->run(['index', 'index']));

		$this->object->getEngine()->wrapWith(['wrapper', 'fallback']);
		$this->assertEquals('<fallbackLayout><fallbackWrapper><wrapper>view.tpl</wrapper></fallbackWrapper></fallbackLayout>', $this->object->run(['index', 'view']));

		$this->object->getEngine()->wrapWith(false)->useLayout(false);
		$this->assertEquals('view.xml.tpl', $this->object->run(['index', 'view', 'ext' => 'xml']));
	}

}