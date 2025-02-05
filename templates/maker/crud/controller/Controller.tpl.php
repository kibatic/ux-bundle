<?= "<?php\n" ?>

namespace <?= $class_data->getNamespace() ?>;

<?= $class_data->getUseStatements(); ?>

<?= $class_data->getClassDeclaration() ?>
{
<?= $generator->generateRouteForControllerMethod($route_path, sprintf('%s_index', $route_name), ['GET']) ?>
<?php if (isset($repository_full_class_name)): ?>
    public function index(<?= $repository_class_name ?> $<?= $repository_var ?>): Response
    {
        return $this->render('<?= $templates_path ?>/index.html.twig', [
            '<?= $entity_twig_var_plural ?>' => $<?= $repository_var ?>->findAll(),
        ]);
    }
<?php else: ?>
    public function index(EntityManagerInterface $entityManager): Response
    {
        $<?= $entity_var_plural ?> = $entityManager
            ->getRepository(<?= $entity_class_name ?>::class)
            ->findAll();

        return $this->render('<?= $templates_path ?>/index.html.twig', [
            '<?= $entity_twig_var_plural ?>' => $<?= $entity_var_plural ?>,
        ]);
    }
<?php endif ?>

<?= $generator->generateRouteForControllerMethod("$route_path/new", sprintf('%s_new', $route_name), ['GET', 'POST']) ?>
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $<?= $entity_var_singular ?> = new <?= $entity_class_name ?>();
        $form = $this->createForm(<?= $form_class_name ?>::class, $<?= $entity_var_singular ?>);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($<?= $entity_var_singular ?>);
            $entityManager->flush();

            $this->addToastAlert('success', '<?= strtoupper($entity_var_singular) ?> créé.');
            return $this->redirectToRoute('<?= $route_name ?>_show', ['id' => $<?= $entity_var_singular ?>->getId()]);
        }

        return $this->render('<?= $templates_path ?>/new.html.twig', [
            '<?= $entity_twig_var_singular ?>' => $<?= $entity_var_singular ?>,
            'form' => $form,
        ]);
    }

<?= $generator->generateRouteForControllerMethod(sprintf("$route_path/{%s}", $entity_identifier), sprintf('%s_show', $route_name), ['GET']) ?>
    public function show(<?= $entity_class_name ?> $<?= $entity_var_singular ?>): Response
    {
        return $this->render('<?= $templates_path ?>/show.html.twig', [
            '<?= $entity_twig_var_singular ?>' => $<?= $entity_var_singular ?>,
        ]);
    }

<?= $generator->generateRouteForControllerMethod(sprintf("$route_path/{%s}/edit", $entity_identifier), sprintf('%s_edit', $route_name), ['GET', 'POST']) ?>
    public function edit(Request $request, <?= $entity_class_name ?> $<?= $entity_var_singular ?>, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(<?= $form_class_name ?>::class, $<?= $entity_var_singular ?>);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addToastAlert('success', '<?= strtoupper($entity_var_singular) ?> modifié.');
            return $this->redirectToReferrer();
        }

        return $this->render('<?= $templates_path ?>/edit.html.twig', [
            '<?= $entity_twig_var_singular ?>' => $<?= $entity_var_singular ?>,
            'form' => $form,
        ]);
    }

<?= $generator->generateRouteForControllerMethod(sprintf("$route_path/{%s}", $entity_identifier), sprintf('%s_delete', $route_name), ['POST']) ?>
    public function delete(Request $request, <?= $entity_class_name ?> $<?= $entity_var_singular ?>, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$<?= $entity_var_singular ?>->get<?= ucfirst($entity_identifier) ?>(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($<?= $entity_var_singular ?>);
            $entityManager->flush();

            $this->addToastAlert('success', '<?= strtoupper($entity_var_singular) ?> supprimé.');
        }

        return $this->redirectToRoute('<?= $route_name ?>_index');
    }
}
