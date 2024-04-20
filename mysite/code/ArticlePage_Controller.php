<?php
class ArticlePage_Controller extends Page_Controller
{
    private static $allowed_actions = array(
        'commentForm',
    );

    public function commentForm()
    {
        $form = Form::create(
            $this,
            __FUNCTION__,
            FieldList::create(
                TextField::create('Name', ''),
                EmailField::create('Email', ''),
                TextAreaField::create('Comment', ''),

            ),
            FieldList::create(
                FormAction::create('HandleComment', 'Post Comment')
                    ->setUseButtonTag(true)
                    ->addExtraClass('btn btn-dafault-color btn-lg')
            ),
            RequiredFields::create('Name', 'Email', 'Comment')
        )->addExtraClass('form-style')->addExtraClass('col-sm-12');

        foreach ($form->Fields() as $field) {
            $field->addExtraClass('form-control')
                ->setAttribute('placeholder', $field->getName() . '*');
        }

        $data = Session::get("FormData.{$form->getName()}.data");

        return $data ? $form->loadDataFrom($data) : $form;
    }

    public function handleComment($data, $form)
    {
        Session::set("FormData.{$form->getName()}.data", $data);
        $existing = $this->Comments()->filter(array(
            'Comment' => $data['Comment'],
        ));

        if ($existing->exists() && strlen($data['Comment']) > 20) {
            $form->sessionMessage('You have already posted this comment', 'bad');
            return $this->redirectBack();
        }
        $comment = ArticleCommentData::create();
        $comment->ArticlePageID = $this->ID;
        $form->saveInto($comment);
        $comment->write();

        Session::clear("FormData.{$form->getName()}.data");
        $form->sessionMessage('Thanks for your comment', 'good');
        return $this->redirectBack();
    }
}
