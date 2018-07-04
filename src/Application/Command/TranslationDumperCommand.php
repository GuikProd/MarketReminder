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

use App\Application\Command\Interfaces\TranslationDumperCommandInterface;
use App\Infra\GCP\CloudTranslation\Helper\Interfaces\CloudTranslationTranslatorInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class TranslationDumperCommand.
 *
 * @author Guillaume Loulier <guillaume.loulier@guikprod.com>
 */
class TranslationDumperCommand extends AbstractTranslationCommand implements TranslationDumperCommandInterface
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
     * @var CloudTranslationTranslatorInterface
     */
    private $cloudTranslationTranslator;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        string $acceptedChannels,
        string $acceptedLocales,
        CloudTranslationTranslatorInterface $translator
    ) {
        $this->acceptedChannels = $acceptedChannels;
        $this->acceptedLocales = $acceptedLocales;
        $this->cloudTranslationTranslator = $translator;

        parent::__construct($this->acceptedChannels, $this->acceptedLocales);
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:translation:dump')
            ->setHelp('This command allow to translate a file using GCP Cloud Translation API, the content is dumped into a file')
            ->addArgument('channel', InputArgument::REQUIRED)
            ->addArgument('locale', InputArgument::REQUIRED);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->checkChannelsAndLocales($input->getArgument('channel'), $input->getArgument('locale'));

        $output->writeln('</info>The translation process is about to begin</info>');

        $this->cloudTranslationTranslator->warmTranslations(
            $input->getArgument('locale'),
            $input->getArgument('channel')
        );

        $output->writeln('</info>The translation process is finished</info>');
    }
}
