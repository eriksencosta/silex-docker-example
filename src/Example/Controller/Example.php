<?php

namespace Example\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Response;

class Example
{
    public function issues(Application $app)
    {
        $statement = $app['db']->query('SELECT * FROM issues');
        $issues = $statement->fetchAll(\PDO::FETCH_ASSOC);

        return $app['twig']->render('issues.twig', array('issues' => $issues, 'url' => $app['url_generator']));
    }

    public function issue(Application $app, $id)
    {
        $issue = $app['db']->fetchAssoc('SELECT * FROM issues WHERE id = ?', array($id));

        if (!$issue) {
            $app->abort(Response::HTTP_NOT_FOUND, sprintf('No issue with the id #%d was found.', $id));
        }

        return $app['twig']->render('issue.twig', array('issue' => $issue, 'url' => $app['url_generator']));
    }
}
