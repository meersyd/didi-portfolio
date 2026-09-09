<?php

namespace Tests\Unit;

use App\Support\VideoEmbed;
use Tests\TestCase;

class VideoEmbedTest extends TestCase
{
    public function test_blank_values_return_null(): void
    {
        $this->assertNull(VideoEmbed::from(null));
        $this->assertNull(VideoEmbed::from(''));
        $this->assertNull(VideoEmbed::from('   '));
    }

    public function test_youtube_urls_become_privacy_embeds(): void
    {
        $expected = [
            'type' => 'iframe',
            'src' => 'https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ',
        ];

        $this->assertSame($expected, VideoEmbed::from('https://www.youtube.com/watch?v=dQw4w9WgXcQ'));
        $this->assertSame($expected, VideoEmbed::from('https://youtu.be/dQw4w9WgXcQ'));
        $this->assertSame($expected, VideoEmbed::from('https://www.youtube.com/embed/dQw4w9WgXcQ'));
        $this->assertSame($expected, VideoEmbed::from('https://www.youtube.com/shorts/dQw4w9WgXcQ'));
    }

    public function test_loom_and_vimeo_urls_become_embeds(): void
    {
        $this->assertSame([
            'type' => 'iframe',
            'src' => 'https://www.loom.com/embed/abc123def',
        ], VideoEmbed::from('https://www.loom.com/share/abc123def'));

        $this->assertSame([
            'type' => 'iframe',
            'src' => 'https://player.vimeo.com/video/123456789',
        ], VideoEmbed::from('https://vimeo.com/123456789'));
    }

    public function test_local_files_and_direct_urls_use_a_video_tag(): void
    {
        $local = VideoEmbed::from('assets/projects/fixease/walkthrough.mp4');

        $this->assertSame('file', $local['type']);
        $this->assertStringContainsString('assets/projects/fixease/walkthrough.mp4', $local['src']);

        $remote = VideoEmbed::from('https://cdn.example.com/demo.mp4');

        $this->assertSame([
            'type' => 'file',
            'src' => 'https://cdn.example.com/demo.mp4',
        ], $remote);
    }
}
