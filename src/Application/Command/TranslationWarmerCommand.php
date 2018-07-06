<?php

declare(strict_types=1);

/*
 * This file is part of the MarketReminder project.
 *
 * (c) Guillaume Loulier <guillaume.loulier@guikprod.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Application\Command;

use App\Application\Command\Interfaces\TranslationWarmerCommandInterface;
use App\Infra\GCP\CloudTranslation\Helper\Interfaces\CloudTranslationWarmerInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class TranslationWarmerCommand.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
final class TranslationWarmerCommand extends AbstractTranslationCommand implements TranslationWarmerCommandInterface
{
    /**
     * @var string
     */
    private $acceptedChannels;

    /**
     * @var string
     */
    private $acceptedLocales;

    /**
     * @var CloudTranslationWarmerInterface
     */
    private $cloudTranslationWarmer;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        string $acceptedChannels,
        string $acceptedLocales,
        CloudTranslationWarmerInterface $cloudTranslationWarmer
    ) {
        $this->acceptedChannels = $acceptedChannels;
        $this->acceptedLocales = $acceptedLocales;
        $this->cloudTranslationWarmer = $cloudTranslationWarmer;

        parent::__construct($this->acceptedChannels, $this->acceptedLocales);
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:translation:warm')
            ->setDescription('Allow to store in cache the translations for a given channel and locale.')
            ->setHelp('This command allow to cache every translation which been dumped.')
            ->addArgument('channel', InputArgument::REQUIRED, 'The channel used by the translation file')
            ->addArgument('locale', InputArgument::REQUIRED, 'The locale used by the translation file');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->checkChannelsAndLocales($input->getArgument('channel'), $input->getArgument('locale'));

        $output->writeln('</info>The warm process is about to begin</info>');

        $this->cloudTranslationWarmer->warmTranslationsCache(
            $input->getArgument('channel'),
            $input->getArgument('locale')
        );

        $output->writeln('</info>The warm process is finished</info>');
    }
}
