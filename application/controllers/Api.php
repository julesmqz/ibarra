<?php

class Api extends CI_Controller {

	public function getNews(Type $var = null)
    {
        $post = $this->input->post(NULL, TRUE);
        $this->load->model('newsmodel','news');
        $limit = isset($post['offset']) ? $post['offset'] : 0;
        $limit = isset($post['limit']) ? $post['limit'] : 3;

        $news = $this->news->get_entries($offset,$limit,'news_post.id,news_post.title,news_post.date_created,news_post.body');

        echo json_encode($news);
    }
}
