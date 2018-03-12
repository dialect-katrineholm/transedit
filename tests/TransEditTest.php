<?php
class TransEditTest extends  \Dialect\TransEdit\TestCase {

	/** @test */
	public function it_can_set_key(){
		$key = str_random(5);
		$val = str_random(6);
		transEdit()->setKey($key, $val);

		$this->assertDatabaseHas('transedit_keys', [
			'name' => $key
		]);
		$this->assertDatabaseHas('transedit_translations', [
			'locale_id' => 1,
			'key_id' => 1,
			'value' => $val
		]);
	}
	/** @test */
	public function it_creates_the_locale_if_missing_when_setting_key(){
		$locale = str_random(2);
		$key = str_random(5);
		$val = str_random(6);
		transEdit()->locale($locale)->setKey($key, $val);

		$this->assertDatabaseHas('transedit_locales', [
			'name' => $locale
		]);
	}
	/** @test */
	public function it_can_use_shortcut_to_set_key(){
		$key = str_random(5);
		$val = str_random(6);
		transEdit()->locale('sv')->key($key, $val);

		$this->assertDatabaseHas('transedit_keys', [
			'name' => $key
		]);
		$this->assertDatabaseHas('transedit_translations', [
			'locale_id' => 2,
			'key_id' => 1,
			'value' => $val
		]);
	}
	/** @test */
	public function it_can_get_key(){
		$key = str_random(5);
		$val = str_random(6);
		transEdit()->setKey($key, $val);
		$this->assertEquals(transEdit()->getKey($key), $val);

	}
	/** @test */
	public function it_returns_key_name_if_no_value_was_found(){
		$key = str_random(5);
		$this->assertEquals(transEdit()->getKey($key), $key);
	}
	/** @test */
	public function it_can_use_shortcut_to_get_key(){
		$key = str_random(5);
		$val = str_random(6);
		transEdit()->key($key, $val);
		$this->assertEquals(transEdit()->key($key), $val);

	}
	/** @test */
	public function it_can_get_different_locales_for_the_same_key(){
		$key = str_random(5);
		$val1 = str_random(6);
		$val2 = str_random(7);
		$locale = str_random(3);
		transEdit()->key($key, $val1);
		transEdit()->locale($locale)->key($key, $val2);

		$this->assertEquals(transEdit()->key($key), $val1);
		$this->assertEquals(transEdit()->locale($locale)->key($key), $val2);

	}

	/** @test */
	public function it_returns_vuecomponent_instead_of_text_if_edit_mode_is_on(){
		$key = str_random(5);
		$val = str_random(6);

		transEdit()->key($key, $val);
		$transEdit = new \Dialect\TransEdit\TransEdit(true);

		$this->assertEquals('<transedit key="'.$key.'" val="'.$val.'"></transedit>', $transEdit->key($key));
	}
}