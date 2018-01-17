<?php
declare(strict_types=1);

namespace Miaversa;

use Miaversa\Statico\Post;

function post($file) : Post {
	$meta = '';
	$summary = '';
	$content = '';
	$contentStep = 0;
	$fileLines = file($file);
	foreach($fileLines as $line) {
		if('}' == substr($line, 0, 1)) {
			$meta .= $line;
			$contentStep++;
			continue;
		}
		if('#' == substr($line, 0, 1)) {
			$contentStep++;
			continue;
		}
		if($contentStep < 1) {
			$meta .= $line;
		}
		if($contentStep == 1) {
			$summary .= $line;
		} elseif($contentStep > 1) {
			$content .= $line;
		}
	}
	$meta = json_decode($meta, true);
	$date = \DateTime::createFromFormat('Y-m-d H:i', $meta['datetime']);
	$post = new Post($meta['slug'], $meta['title'], $meta['description'], $date, $summary, $content);
	return $post;
}

function posts() : array {
	$posts = [];
	$glog = CONTENT . '/posts/*.html';
	$files = glob($glog);
	foreach($files as $file) {
		$posts[] = post($file);
	}
	$posts = sort_posts($posts);
	return $posts;
}

function blog_years(array $posts) : array {
	$years = [];
	foreach($posts as $post) {
		$y = $post->dateTime->format('Y');
		if ( ! in_array($y, $years)) {
			$years[] = $y;
		}
	}
	return $years;
}

function blog_months(array $posts) : array {
	$months = [];
	foreach($posts as $post) {
		$y = $post->dateTime->format('Y/m');
		if ( ! in_array($y, $months)) {
			$months[] = $y;
		}
	}
	return $months;
}

function render_post(\Twig_Environment $template, Post $post) : void {
	$content = $template->render('blog/post.html.twig', ['post' => $post]);
	$datepart = $post->dateTime->format("Y/m");
	$dst = "/blog/{$datepart}/{$post->slug}/index.html";
	put_file($dst, $content);
}

function render_blog(\Twig_Environment $template) : void {
	$posts = posts();
	$content = $template->render('blog/index.html.twig', ['posts' => $posts]);
	put_file("/blog/index.html", $content);
	foreach($posts as $post) {
		render_post($template, $post);
	}
	render_blog_years($template, $posts);
	render_blog_months($template, $posts);
}

function render_blog_years(\Twig_Environment $template, array $posts) : void {
	$posts = posts();
	$years = blog_years($posts);
	foreach($years as $y) {
		$p = filter_posts_in_year($posts, $y);
		$content = $template->render('blog/index.html.twig', ['posts' => $p]);
		$dst = "/blog/{$y}/index.html";
		put_file($dst, $content);
	}
}

function render_blog_months(\Twig_Environment $template, array $posts) : void {
	$posts = posts();
	$months = blog_months($posts);
	foreach($months as $m) {
		$p = filter_posts_in_month($posts, $m);
		$content = $template->render('blog/index.html.twig', ['posts' => $p]);
		$dst = "/blog/{$m}/index.html";
		put_file($dst, $content);
	}
}
