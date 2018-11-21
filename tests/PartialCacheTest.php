<?php

namespace Spatie\PartialCache\Tests;

use Spatie\PartialCache\Tests\TestCase;
use Spatie\PartialCache\PartialCache;
use Illuminate\Support\Facades\Cache;
use Spatie\PartialCache\Tests\Fixtures\ArrayNoTagStore;

class PartialCacheTest extends TestCase
{
    /**
     * @test
     */
    public function i_will_get_the_cache()
    {
        $this->markTestIncomplete('todo');
    }

    /**
     * @test
     */
    public function i_will_not_get_any_cache_if_the_cache_is_disabled()
    {
        config(['partialcache.enabled' => false]);

        $view = $this->app->make(\Illuminate\Contracts\View\Factory::class);

        $cache = \Mockery::mock(\Illuminate\Contracts\Cache\Repository::class);
        $cache->shouldNotHaveReceived('remember');
        $cache->shouldNotHaveReceived('rememberForever');
        $cacheManager = $this->app->make(\Illuminate\Contracts\Cache\Factory::class);
        $config = $this->app->make(\Illuminate\Contracts\Config\Repository::class);

        $this->setNoTagsCacheStore($cacheManager, $config);

        $view->addLocation(__DIR__.'/fixtures');

        $partialcache = new PartialCache($view, $cache, $cacheManager, $config);

        $result = $partialcache->cache([], 'partialcachetestview');
        $this->assertInternalType('string', $result);

        $this->assertStringStartsWith('Start', $result);
        $this->assertStringEndsWith('End', $result);
    }

    /**
     * @test
     */
    public function i_will_get_the_view_if_the_cache_doesnt_exist()
    {
        $view = $this->app->make(\Illuminate\Contracts\View\Factory::class);
        $cache = $this->app->make(\Illuminate\Contracts\Cache\Repository::class);
        $cacheManager = $this->app->make(\Illuminate\Contracts\Cache\Factory::class);
        $config = $this->app->make(\Illuminate\Contracts\Config\Repository::class);

        $this->setNoTagsCacheStore($cacheManager, $config);

        $view->addLocation(__DIR__.'/fixtures');

        $partialcache = new PartialCache($view, $cache, $cacheManager, $config);

        $result = $partialcache->cache([], 'partialcachetestview');
        $this->assertInternalType('string', $result);

        $this->assertStringStartsWith('Start', $result);
        $this->assertStringEndsWith('End', $result);
    }

    /**
     * @test
     */
    public function i_will_remember_the_cache_for_the_provided_minuts()
    {
        $testResult = str_random();
        $minuts = mt_rand(10, 100);

        $view = $this->app->make(\Illuminate\Contracts\View\Factory::class);

        $cache = \Mockery::mock(\Illuminate\Contracts\Cache\Repository::class);
        $cache->shouldNotHaveReceived('rememberForever');
        $cache->shouldReceive('tags')->andReturnSelf();
        $cache->shouldReceive('remember')->once()->with(\Mockery::any(), $minuts, \Mockery::any())->andReturn($testResult);

        $cacheManager = $this->app->make(\Illuminate\Contracts\Cache\Factory::class);
        $config = $this->app->make(\Illuminate\Contracts\Config\Repository::class);

        $this->setNoTagsCacheStore($cacheManager, $config);

        $view->addLocation(__DIR__.'/fixtures');

        $partialcache = new PartialCache($view, $cache, $cacheManager, $config);
        $result = $partialcache->cache([], 'partialcachetestview', null, $minuts);
        $this->assertEquals($testResult, $result);
    }

    /**
     * @test
     */
    public function i_will_rember_the_cache_forever_if_wanted()
    {
        $testResult = str_random();

        $view = $this->app->make(\Illuminate\Contracts\View\Factory::class);

        $cache = \Mockery::mock(\Illuminate\Contracts\Cache\Repository::class);
        $cache->shouldNotHaveReceived('remember');
        $cache->shouldReceive('tags')->andReturnSelf();
        $cache->shouldReceive('rememberForever')->once()->andReturn($testResult);

        $cacheManager = $this->app->make(\Illuminate\Contracts\Cache\Factory::class);
        $config = $this->app->make(\Illuminate\Contracts\Config\Repository::class);

        $this->setNoTagsCacheStore($cacheManager, $config);

        $view->addLocation(__DIR__.'/fixtures');

        $partialcache = new PartialCache($view, $cache, $cacheManager, $config);
        $result = $partialcache->cache([], 'partialcachetestview');
        $this->assertEquals($testResult, $result);
    }

    /**
     * @test
     */
    public function i_will_use_the_tags_for_the_cache_if_possible_forever()
    {
        $testResult = str_random();

        $view = $this->app->make(\Illuminate\Contracts\View\Factory::class);

        $cache = \Mockery::mock(\Illuminate\Contracts\Cache\Repository::class);
        $cache->shouldNotHaveReceived('remember');
        $cache->shouldReceive('tags')->andReturnSelf();
        $cache->shouldReceive('rememberForever')->once()->andReturn($testResult);

        $cacheManager = $this->app->make(\Illuminate\Contracts\Cache\Factory::class);
        $config = $this->app->make(\Illuminate\Contracts\Config\Repository::class);

        $view->addLocation(__DIR__.'/fixtures');

        $partialcache = new PartialCache($view, $cache, $cacheManager, $config);
        $result = $partialcache->cache([], 'partialcachetestview', null, null, null, 'testtag');
        $this->assertEquals($testResult, $result);
    }

    /**
     * @test
     */
    public function i_will_use_the_tags_for_the_cache_if_possible_with_minutes()
    {
        $testResult = str_random();
        $minuts = mt_rand(10, 100);

        $view = $this->app->make(\Illuminate\Contracts\View\Factory::class);

        $cache = \Mockery::mock(\Illuminate\Contracts\Cache\Repository::class);
        $cache->shouldNotHaveReceived('rememberForever');
        $cache->shouldReceive('tags')->andReturnSelf();
        $cache->shouldReceive('remember')->once()->with(\Mockery::any(), $minuts, \Mockery::any())->andReturn($testResult);

        $cacheManager = $this->app->make(\Illuminate\Contracts\Cache\Factory::class);
        $config = $this->app->make(\Illuminate\Contracts\Config\Repository::class);

        $view->addLocation(__DIR__.'/fixtures');

        $partialcache = new PartialCache($view, $cache, $cacheManager, $config);
        $result = $partialcache->cache([], 'partialcachetestview', null, $minuts, null, 'testtag');
        $this->assertEquals($testResult, $result);
    }

    /**
     * @test
     */
    public function i_will_forget_the_cache_if_wanted()
    {
        $this->markTestIncomplete('todo');
    }

    /**
     * @test
     */
    public function i_will_forget_the_cache_if_wanted_with_tags()
    {
        $this->markTestIncomplete('todo');
    }

    /**
     * @test
     */
    public function i_will_flush_the_cache()
    {
        $this->markTestIncomplete('todo');
    }

    /**
     * @test
     */
    public function i_will_not_flush_the_cache_if_we_dont_have_tags()
    {
        $this->markTestIncomplete('todo');
    }

    /**
     * Set the cache store to an array store without tags
     *
     * @param \Illuminate\Contracts\Cache\Factory $cacheManager
     * @param \Illuminate\Contracts\Config\Repository $config
     *
     * @return void
     */
    protected function setNoTagsCacheStore($cacheManager, $config)
    {
        $cacheManager->extend('arraynotags', function ($app){
            return Cache::repository(new ArrayNoTagStore());
        });

        $cacheStores = $config->get('cache.stores', []);
        $cacheStores['arraynotags'] = ['driver' => 'arraynotags'];
        $config->set('cache.stores', $cacheStores);
        $config->set('cache.default', 'arraynotags');
    }
}