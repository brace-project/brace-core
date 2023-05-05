<?php

namespace Brace\Core;

enum EnvironmentType : string
{
    case PRODUCTION = "production";
    case DEVELOPMENT = "development";
    case TEST = "test";
}
