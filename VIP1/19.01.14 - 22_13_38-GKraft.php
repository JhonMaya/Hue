<?php exit() ?>--by GKraft 178.27.31.102
--[[
    Packet - PKT_S2C_UpdateUnit 1.0.1 by Husky
    ========================================================================

    This is a plugin for the Packet library.

    It adds the ability to decode PKT_S2C_UpdateUnit packets with the packet
    library.


    Changelog
    ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    1.0     - initial release with the most important features

    1.0.1   - updated header for new update
]]

Packet.headers.PKT_S2C_UpdateUnit = 0XC3

local masterMasks = {
    ['Summoner\'s Rift'] = {
        obj_AI_Hero = {
            {
                0x01, {
                  --[[name                decodeFlag]]--
                    {'mGold',                       1},
                    {'mGoldTotal',                  1},
                    {'mCanCastBits1',               0},
                    {'mCanCastBits2',               0},
                    {'mEvolvePoints',               0},
                    {'mEvolveFlag',                 0},
                    {'ManaCost_0',                  1},
                    {'ManaCost_1',                  1},
                    {'ManaCost_2',                  1},
                    {'ManaCost_3',                  1},
                    {'ManaCost_Ex0',                1},
                    {'ManaCost_Ex1',                1},
                    {'ManaCost_Ex2',                1},
                    {'ManaCost_Ex3',                1},
                    {'ManaCost_Ex4',                1},
                    {'ManaCost_Ex5',                1},
                    {'ManaCost_Ex6',                1},
                    {'ManaCost_Ex7',                1},
                    {'ManaCost_Ex8',                1},
                    {'ManaCost_Ex9',                1},
                    {'ManaCost_Ex10',               1},
                    {'ManaCost_Ex11',               1},
                    {'ManaCost_Ex12',               1},
                    {'ManaCost_Ex13',               1},
                    {'ManaCost_Ex14',               1},
                    {'ManaCost_Ex15',               1}
                }
            }, {
                0x02, {
                  --[[name                decodeFlag]]--
                    {'ActionState',                 0},
                    {'MagicImmune',                 0},
                    {'IsInvulnerable',              0},
                    {'IsPhysicalImmune',            0},
                    {'mBaseAttackDamage',           1},
                    {'mBaseAbilityDamage',          1},
                    {'mDodge',                      1},
                    {'mCrit',                       1},
                    {'mArmor',                      1},
                    {'mSpellBlock',                 1},
                    {'mHPRegenRate',                1},
                    {'mPARRegenRate',               1},
                    {'mAttackRange',                1},
                    {'mFlatPhysicalDamageMod',      1},
                    {'mPercentPhysicalDamageMod',   1},
                    {'mFlatMagicDamageMod',         1},
                    {'mFlatMagicReduction',         1},
                    {'mPercentMagicReduction',      1},
                    {'mAttackSpeedMod',             1},
                    {'mFlatCastRangeMod',           1},
                    {'mPercentCooldownMod',         1},
                    {'mPassiveCooldownEndTime',     1},
                    {'mPassiveCooldownTotalTime',   1},
                    {'mFlatArmorPenetration',       1},
                    {'mPercentArmorPenetration',    1},
                    {'mFlatMagicPenetration',       1},
                    {'mPercentMagicPenetration',    1},
                    {'mPercentLifeStealMod',        1},
                    {'mPercentSpellVampMod',        1},
                    {'mPercentCCReduction',         1}
                }
            }, {
                0x04, {}
            }, {
                0x08, {
                  --[[name                decodeFlag]]--
                    {'mHP',                         1},
                    {'mMP',                         1},
                    {'mMaxHP',                      1},
                    {'mMaxMP',                      1},
                    {'mExp',                        1},
                    {'mLifetime',                   1},
                    {'mMaxLifetime',                1},
                    {'mLifetimeTicks',              1},
                    {'mFlatBubbleRadiusMod',        1},
                    {'mPercentBubbleRadiusMod',     1},
                    {'mMoveSpeed',                  1},
                    {'mCrit',                       1},
                    {'mPathfindingRadiusMod',       1},
                    {'mLevelRef',                   0},
                    {'mNumNeutralMinionsKilled',    0},
                    {'mIsTargetable',               0},
                    {'mIsTargetableToTeamFlags',    0}
                }
            }, {
                0x20, {}
            }, {
                0x10, {}
            }
        },
        obj_AI_Minion = {
            {
                0x01, {}
            }, {
                0x02, {
                  --[[name                decodeFlag]]--
                    {'mHP',                         1},
                    {'mMaxHP',                      1},
                    {'mLifetime',                   1},
                    {'mMaxLifetime',                1},
                    {'mLifetimeTicks',              1},
                    {'mMaxMP',                      1},
                    {'mMP',                         1},
                    {'ActionState',                 0},
                    {'MagicImmune',                 0},
                    {'IsInvulnerable',              0},
                    {'IsPhysicalImmune',            0},
                    {'mBaseAttackDamage',           1},
                    {'mArmor',                      1},
                    {'mSpellBlock',                 1},
                    {'mAttackSpeedMod',             1},
                    {'mFlatPhysicalDamageMod',      1},
                    {'mPercentPhysicalDamageMod',   1},
                    {'mFlatMagicDamageMod',         1},
                    {'mHPRegenRate',                1},
                    {'mPARRegenRate',               1},
                    {'mFlatMagicReduction',         1},
                    {'mPercentMagicReduction',      1}
                }
            }, {
                0x04, {}
            }, {
                0x08, {
                  --[[name                decodeFlag]]--
                    {'mFlatBubbleRadiusMod',        1},
                    {'mPercentBubbleRadiusMod',     1},
                    {'mMoveSpeed',                  1},
                    {'mCrit',                       1},
                    {'mIsTargetable',               0},
                    {'mIsTargetableToTeamFlags',    0}
                }
            }, {
                0x20, {}
            }, {
                0x10, {}
            }
        },
        obj_AI_Turret = {
            {
                0x01, {}
            }, {
                0x02, {
                  --[[name                decodeFlag]]--
                    {'mHP',                         1},
                    {'mMaxHP',                      1},
                    {'ActionState',                 0},
                    {'MagicImmune',                 0},
                    {'IsInvulnerable',              0},
                    {'IsPhysicalImmune',            0},
                    {'mBaseAttackDamage',           1},
                    {'mArmor',                      1},
                    {'mSpellBlock',                 1},
                    {'mAttackSpeedMod',             1},
                    {'mFlatPhysicalDamageMod',      1},
                    {'mPercentPhysicalDamageMod',   1},
                    {'mFlatMagicDamageMod',         1},
                    {'mHPRegenRate',                1}
                }
            }, {
                0x04, {}
            }, {
                0x08, {
                  --[[name                decodeFlag]]--
                    {'mFlatBubbleRadiusMod',        1},
                    {'mPercentBubbleRadiusMod',     1},
                    {'mMoveSpeed',                  1},
                    {'mCrit',                       1}
                }
            }, {
                0x20, {
                  --[[name                decodeFlag]]--
                    {'mIsTargetable',               0},
                    {'mIsTargetableToTeamFlags',    0}
                }
            }, {
                0x10, {}
            }
        }
    },
    ['The Twisted Treeline Beta'] = {
        obj_Barracks = {
            {
                0x01, {}
            }, {
                0x02, {
                  --[[name                decodeFlag]]--
                    {'mHP',                         1},
                    {'IsInvulnerable',              0}
                }
            }, {
                0x04, {}
            }, {
                0x08, {}
            }, {
                0x20, {
                  --[[name                decodeFlag]]--
                    {'mIsTargetable',               0},
                    {'mIsTargetableToTeamFlags',    0}
                }
            }, {
                0x10, {}
            }
        },
        obj_AI_Minion = {
            {
                0x01, {}
            }, {
                0x02, {
                  --[[name                decodeFlag]]--
                    {'mHP',                         1},
                    {'mMaxHP',                      1},
                    {'mLifetime',                   1},
                    {'mMaxLifetime',                1},
                    {'mLifetimeTicks',              1},
                    {'ActionState',                 0},
                    {'MagicImmune',                 0},
                    {'IsInvulnerable',              0},
                    {'IsPhysicalImmune',            0},
                    {'mBaseAttackDamage',           1},
                    {'mArmor',                      1},
                    {'mSpellBlock',                 1},
                    {'mAttackSpeedMod',             1},
                    {'mFlatPhysicalDamageMod',      1},
                    {'mPercentPhysicalDamageMod',   1},
                    {'mFlatMagicDamageMod',         1},
                    {'mHPRegenRate',                1},
                    {'mPARRegenRate',               1},
                    {'mFlatMagicReduction',         1},
                    {'mPercentMagicReduction',      1},
                    {'mMaxMP',                      1},
                    {'mMP',                         1}
                }
            }, {
                0x04, {}
            }, {
                0x08, {
                  --[[name                decodeFlag]]--
                    {'mMaxMP',                      1},
                    {'mMP',                         1},
                    {'mFlatBubbleRadiusMod',        1},
                    {'mPercentBubbleRadiusMod',     1},
                    {'mMoveSpeed',                  1},
                    {'mCrit',                       1},
                    {'mIsTargetable',               0},
                    {'mIsTargetableToTeamFlags',    0}
                }
            }, {
                0x20, {}
            }, {
                0x10, {}
            }
        },
        obj_Shop = {
            {
                0x01, {}
            }, {
                0x02, {}
            }, {
                0x04, {}
            }, {
                0x08, {}
            }, {
                0x20, {}
            }, {
                0x10, {}
            }
        },
        obj_BarracksDampener = {
            {
                0x01, {}
            }, {
                0x02, {
                  --[[name                decodeFlag]]--
                    {'mHP',                         1},
                    {'IsInvulnerable',              0}
                }
            }, {
                0x04, {}
            }, {
                0x08, {}
            }, {
                0x20, {
                  --[[name                decodeFlag]]--
                    {'mIsTargetable',               0},
                    {'mIsTargetableToTeamFlags',    0}
                }
            }, {
                0x10, {}
            }
        },
        obj_AI_Hero = {
            {
                0x01, {
                  --[[name                decodeFlag]]--
                    {'mGold',                       1},
                    {'mGoldTotal',                  1},
                    {'mCanCastBits1',               0},
                    {'mCanCastBits2',               0},
                    {'mEvolvePoints',               0},
                    {'mEvolveFlag',                 0},
                    {'ManaCost_0',                  1},
                    {'ManaCost_1',                  1},
                    {'ManaCost_2',                  1},
                    {'ManaCost_3',                  1},
                    {'ManaCost_Ex0',                1},
                    {'ManaCost_Ex1',                1},
                    {'ManaCost_Ex2',                1},
                    {'ManaCost_Ex3',                1},
                    {'ManaCost_Ex4',                1},
                    {'ManaCost_Ex5',                1},
                    {'ManaCost_Ex6',                1},
                    {'ManaCost_Ex7',                1},
                    {'ManaCost_Ex8',                1},
                    {'ManaCost_Ex9',                1},
                    {'ManaCost_Ex10',               1},
                    {'ManaCost_Ex11',               1},
                    {'ManaCost_Ex12',               1},
                    {'ManaCost_Ex13',               1},
                    {'ManaCost_Ex14',               1},
                    {'ManaCost_Ex15',               1}
                }
            }, {
                0x02, {
                  --[[name                decodeFlag]]--
                    {'ActionState',                 0},
                    {'MagicImmune',                 0},
                    {'IsInvulnerable',              0},
                    {'IsPhysicalImmune',            0},
                    {'mBaseAttackDamage',           1},
                    {'mBaseAbilityDamage',          1},
                    {'mDodge',                      1},
                    {'mCrit',                       1},
                    {'mArmor',                      1},
                    {'mSpellBlock',                 1},
                    {'mHPRegenRate',                1},
                    {'mPARRegenRate',               1},
                    {'mAttackRange',                1},
                    {'mFlatPhysicalDamageMod',      1},
                    {'mPercentPhysicalDamageMod',   1},
                    {'mFlatMagicDamageMod',         1},
                    {'mFlatMagicReduction',         1},
                    {'mPercentMagicReduction',      1},
                    {'mAttackSpeedMod',             1},
                    {'mFlatCastRangeMod',           1},
                    {'mPercentCooldownMod',         1},
                    {'mPassiveCooldownEndTime',     1},
                    {'mPassiveCooldownTotalTime',   1},
                    {'mFlatArmorPenetration',       1},
                    {'mPercentArmorPenetration',    1},
                    {'mFlatMagicPenetration',       1},
                    {'mPercentMagicPenetration',    1},
                    {'mPercentLifeStealMod',        1},
                    {'mPercentSpellVampMod',        1},
                    {'mPercentCCReduction',         1}
                }
            }, {
                0x04, {}
            }, {
                0x08, {
                  --[[name                decodeFlag]]--
                    {'mHP',                         1},
                    {'mMP',                         1},
                    {'mMaxHP',                      1},
                    {'mMaxMP',                      1},
                    {'mExp',                        1},
                    {'mLifetime',                   1},
                    {'mMaxLifetime',                1},
                    {'mLifetimeTicks',              1},
                    {'mFlatBubbleRadiusMod',        1},
                    {'mPercentBubbleRadiusMod',     1},
                    {'mMoveSpeed',                  1},
                    {'mCrit',                       1},
                    {'mPathfindingRadiusMod',       1},
                    {'mLevelRef',                   0},
                    {'mNumNeutralMinionsKilled',    0},
                    {'mIsTargetable',               0},
                    {'mIsTargetableToTeamFlags',    0}
                }
            }, {
                0x20, {}
            }, {
                0x10, {}
            }
        },
        obj_AI_Turret = {
            {
                0x01, {}
            }, {
                0x02, {
                  --[[name                decodeFlag]]--
                    {'mHP',                         1},
                    {'mMaxHP',                      1},
                    {'ActionState',                 0},
                    {'MagicImmune',                 0},
                    {'IsInvulnerable',              0},
                    {'IsPhysicalImmune',            0},
                    {'mBaseAttackDamage',           1},
                    {'mArmor',                      1},
                    {'mSpellBlock',                 1},
                    {'mAttackSpeedMod',             1},
                    {'mFlatPhysicalDamageMod',      1},
                    {'mPercentPhysicalDamageMod',   1},
                    {'mFlatMagicDamageMod',         1},
                    {'mHPRegenRate',                1}
                }
            }, {
                0x04, {}
            }, {
                0x08, {
                  --[[name                decodeFlag]]--
                    {'mFlatBubbleRadiusMod',        1},
                    {'mPercentBubbleRadiusMod',     1},
                    {'mMoveSpeed',                  1},
                    {'mCrit',                       1}
                }
            }, {
                0x20, {
                  --[[name                decodeFlag]]--
                    {'mIsTargetable',               0},
                    {'mIsTargetableToTeamFlags',    0}
                }
            }, {
                0x10, {}
            }
        }
    },
    ['The Crystal Scar'] = {
        obj_AI_Turret = {
            {
                0x01, {}
            }, {
                0x02, {
                  --[[name                decodeFlag]]--
                    {'mHP',                         1},
                    {'mMaxHP',                      1},
                    {'ActionState',                 0},
                    {'MagicImmune',                 0},
                    {'IsInvulnerable',              0},
                    {'IsPhysicalImmune',            0},
                    {'mBaseAttackDamage',           1},
                    {'mArmor',                      1},
                    {'mSpellBlock',                 1},
                    {'mAttackSpeedMod',             1},
                    {'mFlatPhysicalDamageMod',      1},
                    {'mPercentPhysicalDamageMod',   1},
                    {'mFlatMagicDamageMod',         1},
                    {'mHPRegenRate',                1}
                }
            }, {
                0x04, {}
            }, {
                0x08, {
                  --[[name                decodeFlag]]--
                    {'mFlatBubbleRadiusMod',        1},
                    {'mPercentBubbleRadiusMod',     1},
                    {'mMoveSpeed',                  1},
                    {'mCrit',                       1}
                }
            }, {
                0x20, {
                  --[[name                decodeFlag]]--
                    {'mIsTargetable',               0},
                    {'mIsTargetableToTeamFlags',    0}
                }
            }, {
                0x10, {}
            }
        },
        obj_AI_Minion = {
            {
                0x01, {}
            }, {
                0x02, {
                  --[[name                decodeFlag]]--
                    {'mHP',                         1},
                    {'mMaxHP',                      1},
                    {'mLifetime',                   1},
                    {'mMaxLifetime',                1},
                    {'mLifetimeTicks',              1},
                    {'mMaxMP',                      1},
                    {'mMP',                         1},
                    {'ActionState',                 0},
                    {'MagicImmune',                 0},
                    {'IsInvulnerable',              0},
                    {'IsPhysicalImmune',            0},
                    {'mBaseAttackDamage',           1},
                    {'mArmor',                      1},
                    {'mSpellBlock',                 1},
                    {'mAttackSpeedMod',             1},
                    {'mFlatPhysicalDamageMod',      1},
                    {'mPercentPhysicalDamageMod',   1},
                    {'mFlatMagicDamageMod',         1},
                    {'mHPRegenRate',                1},
                    {'mPARRegenRate',               1},
                    {'mFlatMagicReduction',         1},
                    {'mPercentMagicReduction',      1}
                }
            }, {
                0x04, {}
            }, {
                0x08, {
                  --[[name                decodeFlag]]--
                    {'mFlatBubbleRadiusMod',        1},
                    {'mPercentBubbleRadiusMod',     1},
                    {'mMoveSpeed',                  1},
                    {'mCrit',                       1},
                    {'mIsTargetable',               0},
                    {'mIsTargetableToTeamFlags',    0}
                }
            }, {
                0x20, {}
            }, {
                0x10, {}
            }
        },
        obj_AI_Hero = {
            {
                0x01, {
                  --[[name                decodeFlag]]--
                    {'mGold',                       1},
                    {'mGoldTotal',                  1},
                    {'mCanCastBits1',               0},
                    {'mCanCastBits2',               0},
                    {'mEvolvePoints',               0},
                    {'mEvolveFlag',                 0},
                    {'ManaCost_0',                  1},
                    {'ManaCost_1',                  1},
                    {'ManaCost_2',                  1},
                    {'ManaCost_3',                  1},
                    {'ManaCost_Ex0',                1},
                    {'ManaCost_Ex1',                1},
                    {'ManaCost_Ex2',                1},
                    {'ManaCost_Ex3',                1},
                    {'ManaCost_Ex4',                1},
                    {'ManaCost_Ex5',                1},
                    {'ManaCost_Ex6',                1},
                    {'ManaCost_Ex7',                1},
                    {'ManaCost_Ex8',                1},
                    {'ManaCost_Ex9',                1},
                    {'ManaCost_Ex10',               1},
                    {'ManaCost_Ex11',               1},
                    {'ManaCost_Ex12',               1},
                    {'ManaCost_Ex13',               1},
                    {'ManaCost_Ex14',               1},
                    {'ManaCost_Ex15',               1}
                }
            }, {
                0x02, {
                  --[[name                decodeFlag]]--
                    {'ActionState',                 0},
                    {'MagicImmune',                 0},
                    {'IsInvulnerable',              0},
                    {'IsPhysicalImmune',            0},
                    {'mBaseAttackDamage',           1},
                    {'mBaseAbilityDamage',          1},
                    {'mDodge',                      1},
                    {'mCrit',                       1},
                    {'mArmor',                      1},
                    {'mSpellBlock',                 1},
                    {'mHPRegenRate',                1},
                    {'mPARRegenRate',               1},
                    {'mAttackRange',                1},
                    {'mFlatPhysicalDamageMod',      1},
                    {'mPercentPhysicalDamageMod',   1},
                    {'mFlatMagicDamageMod',         1},
                    {'mFlatMagicReduction',         1},
                    {'mPercentMagicReduction',      1},
                    {'mAttackSpeedMod',             1},
                    {'mFlatCastRangeMod',           1},
                    {'mPercentCooldownMod',         1},
                    {'mPassiveCooldownEndTime',     1},
                    {'mPassiveCooldownTotalTime',   1},
                    {'mFlatArmorPenetration',       1},
                    {'mPercentArmorPenetration',    1},
                    {'mFlatMagicPenetration',       1},
                    {'mPercentMagicPenetration',    1},
                    {'mPercentLifeStealMod',        1},
                    {'mPercentSpellVampMod',        1},
                    {'mPercentCCReduction',         1}
                }
            }, {
                0x04, {}
            }, {
                0x08, {
                  --[[name                decodeFlag]]--
                    {'mHP',                         1},
                    {'mMP',                         1},
                    {'mMaxHP',                      1},
                    {'mMaxMP',                      1},
                    {'mExp',                        1},
                    {'mLifetime',                   1},
                    {'mMaxLifetime',                1},
                    {'mLifetimeTicks',              1},
                    {'mFlatBubbleRadiusMod',        1},
                    {'mPercentBubbleRadiusMod',     1},
                    {'mMoveSpeed',                  1},
                    {'mCrit',                       1},
                    {'mPathfindingRadiusMod',       1},
                    {'mLevelRef',                   0},
                    {'mNumNeutralMinionsKilled',    0},
                    {'mIsTargetable',               0},
                    {'mIsTargetableToTeamFlags',    0}
                }
            }, {
                0x20, {}
            }, {
                0x10, {}
            }
        }
    },
    ['Howling Abyss'] = {
        obj_AI_Minion = {
            {
                0x01, {}
            }, {
                0x02, {
                  --[[name                decodeFlag]]--
                    {'mHP',                         1},
                    {'mMaxHP',                      1},
                    {'mLifetime',                   1},
                    {'mMaxLifetime',                1},
                    {'mLifetimeTicks',              1},
                    {'mMaxMP',                      1},
                    {'mMP',                         1},
                    {'ActionState',                 0},
                    {'MagicImmune',                 0},
                    {'IsInvulnerable',              0},
                    {'IsPhysicalImmune',            0},
                    {'mBaseAttackDamage',           1},
                    {'mArmor',                      1},
                    {'mSpellBlock',                 1},
                    {'mAttackSpeedMod',             1},
                    {'mFlatPhysicalDamageMod',      1},
                    {'mPercentPhysicalDamageMod',   1},
                    {'mFlatMagicDamageMod',         1},
                    {'mHPRegenRate',                1},
                    {'mPARRegenRate',               1},
                    {'mFlatMagicReduction',         1},
                    {'mPercentMagicReduction',      1}
                }
            }, {
                0x04, {}
            }, {
                0x08, {
                  --[[name                decodeFlag]]--
                    {'mFlatBubbleRadiusMod',        1},
                    {'mPercentBubbleRadiusMod',     1},
                    {'mMoveSpeed',                  1},
                    {'mCrit',                       1},
                    {'mIsTargetable',               0},
                    {'mIsTargetableToTeamFlags',    0}
                }
            }, {
                0x20, {}
            }, {
                0x10, {}
            }
        },
        obj_AI_Turret = {
            {
                0x01, {}
            }, {
                0x02, {
                  --[[name                decodeFlag]]--
                    {'mHP',                         1},
                    {'mMaxHP',                      1},
                    {'ActionState',                 0},
                    {'MagicImmune',                 0},
                    {'IsInvulnerable',              0},
                    {'IsPhysicalImmune',            0},
                    {'mBaseAttackDamage',           1},
                    {'mArmor',                      1},
                    {'mSpellBlock',                 1},
                    {'mAttackSpeedMod',             1},
                    {'mFlatPhysicalDamageMod',      1},
                    {'mPercentPhysicalDamageMod',   1},
                    {'mFlatMagicDamageMod',         1},
                    {'mHPRegenRate',                1}
                }
            }, {
                0x04, {}
            }, {
                0x08, {
                  --[[name                decodeFlag]]--
                    {'mFlatBubbleRadiusMod',        1},
                    {'mPercentBubbleRadiusMod',     1},
                    {'mMoveSpeed',                  1},
                    {'mCrit',                       1}
                }
            }, {
                0x20, {
                  --[[name                decodeFlag]]--
                    {'mIsTargetable',               0},
                    {'mIsTargetableToTeamFlags',    0}
                }
            }, {
                0x10, {}
            }
        },
        obj_AI_Hero = {
            {
                0x01, {
                  --[[name                decodeFlag]]--
                    {'mGold',                       1},
                    {'mGoldTotal',                  1},
                    {'mCanCastBits1',               0},
                    {'mCanCastBits2',               0},
                    {'mEvolvePoints',               0},
                    {'mEvolveFlag',                 0},
                    {'ManaCost_0',                  1},
                    {'ManaCost_1',                  1},
                    {'ManaCost_2',                  1},
                    {'ManaCost_3',                  1},
                    {'ManaCost_Ex0',                1},
                    {'ManaCost_Ex1',                1},
                    {'ManaCost_Ex2',                1},
                    {'ManaCost_Ex3',                1},
                    {'ManaCost_Ex4',                1},
                    {'ManaCost_Ex5',                1},
                    {'ManaCost_Ex6',                1},
                    {'ManaCost_Ex7',                1},
                    {'ManaCost_Ex8',                1},
                    {'ManaCost_Ex9',                1},
                    {'ManaCost_Ex10',               1},
                    {'ManaCost_Ex11',               1},
                    {'ManaCost_Ex12',               1},
                    {'ManaCost_Ex13',               1},
                    {'ManaCost_Ex14',               1},
                    {'ManaCost_Ex15',               1}
                }
            }, {
                0x02, {
                  --[[name                decodeFlag]]--
                    {'ActionState',                 0},
                    {'MagicImmune',                 0},
                    {'IsInvulnerable',              0},
                    {'IsPhysicalImmune',            0},
                    {'mBaseAttackDamage',           1},
                    {'mBaseAbilityDamage',          1},
                    {'mDodge',                      1},
                    {'mCrit',                       1},
                    {'mArmor',                      1},
                    {'mSpellBlock',                 1},
                    {'mHPRegenRate',                1},
                    {'mPARRegenRate',               1},
                    {'mAttackRange',                1},
                    {'mFlatPhysicalDamageMod',      1},
                    {'mPercentPhysicalDamageMod',   1},
                    {'mFlatMagicDamageMod',         1},
                    {'mFlatMagicReduction',         1},
                    {'mPercentMagicReduction',      1},
                    {'mAttackSpeedMod',             1},
                    {'mFlatCastRangeMod',           1},
                    {'mPercentCooldownMod',         1},
                    {'mPassiveCooldownEndTime',     1},
                    {'mPassiveCooldownTotalTime',   1},
                    {'mFlatArmorPenetration',       1},
                    {'mPercentArmorPenetration',    1},
                    {'mFlatMagicPenetration',       1},
                    {'mPercentMagicPenetration',    1},
                    {'mPercentLifeStealMod',        1},
                    {'mPercentSpellVampMod',        1},
                    {'mPercentCCReduction',         1}
                }
            }, {
                0x04, {}
            }, {
                0x08, {
                  --[[name                decodeFlag]]--
                    {'mHP',                         1},
                    {'mMP',                         1},
                    {'mMaxHP',                      1},
                    {'mMaxMP',                      1},
                    {'mExp',                        1},
                    {'mLifetime',                   1},
                    {'mMaxLifetime',                1},
                    {'mLifetimeTicks',              1},
                    {'mFlatBubbleRadiusMod',        1},
                    {'mPercentBubbleRadiusMod',     1},
                    {'mMoveSpeed',                  1},
                    {'mCrit',                       1},
                    {'mPathfindingRadiusMod',       1},
                    {'mLevelRef',                   0},
                    {'mNumNeutralMinionsKilled',    0},
                    {'mIsTargetable',               0},
                    {'mIsTargetableToTeamFlags',    0}
                }
            }, {
                0x20, {}
            }, {
                0x10, {}
            }
        },
        obj_HQ = {
            {
                0x01, {}
            }, {
                0x02, {
                  --[[name                decodeFlag]]--
                    {'mHP',                         1},
                    {'IsInvulnerable',              0}
                }
            }, {
                0x04, {}
            }, {
                0x08, {}
            }, {
                0x20, {
                  --[[name                decodeFlag]]--
                    {'mIsTargetable',               0},
                    {'mIsTargetableToTeamFlags',    0}
                }
            }, {
                0x10, {}
            }
        },
        obj_Barracks = {
            {
                0x01, {}
            }, {
                0x02, {
                  --[[name                decodeFlag]]--
                    {'mHP',                         1},
                    {'IsInvulnerable',              0}
                }
            }, {
                0x04, {}
            }, {
                0x08, {}
            }, {
                0x20, {
                  --[[name                decodeFlag]]--
                    {'mIsTargetable',               0},
                    {'mIsTargetableToTeamFlags',    0}
                }
            }, {
                0x10, {}
            }
        },
        obj_BarracksDampener = {
            {
                0x01, {}
            }, {
                0x02, {
                  --[[name                decodeFlag]]--
                    {'mHP',                         1},
                    {'IsInvulnerable',              0}
                }
            }, {
                0x04, {}
            }, {
                0x08, {}
            }, {
                0x20, {
                  --[[name                decodeFlag]]--
                    {'mIsTargetable',               0},
                    {'mIsTargetableToTeamFlags',    0}
                }
            }, {
                0x10, {}
            }
        }
    }
}

Packet.definition.PKT_S2C_UpdateUnit = {
    decode = function(p)
        p.pos = 5

        local packetResult = {
            decodedSuccessfully = true,
            sequenceNumber = p:Decode4(),
            updates = {}
        }

        local updateCount = p:Decode1()

        for k = 1, updateCount do
            packetResult.packetMasterMask = p:Decode1()
            local packetMasterMask = packetResult.packetMasterMask

            local valueTable = {networkId = p:DecodeF()}
            local object = objManager:GetObjectByNetworkId(valueTable.networkId)

            if packetMasterMask ~= 0 then
                for i, subMask in ipairs(masterMasks[GetGame().map.name] and masterMasks[GetGame().map.name][(object and object.valid) and object.type or 'obj_AI_Minion'] or {{0x01, {}}, {0x02, {}}, {0x04, {}}, {0x08, {}}, {0x20, {}}, {0x10, {}}}) do
                    if bit32.band(packetMasterMask, subMask[1]) ~= 0 then
                        local packetSubMask = p:Decode4()
                        local sectionLength = p:Decode1()
                        local startPos = p.pos

                        for subMaskIndex, subMaskEntry in ipairs(subMask[2]) do
                            subMaskIndex = 2 ^ (subMaskIndex - 1)
                            if bit32.band(packetSubMask, subMaskIndex) ~= 0 then
                                if subMaskEntry[2] ~= 0 then
                                    local firstByte = Packet.unsignedToSigned(p:Decode1(), 1)

                                    if firstByte == -1 then
                                        valueTable[subMaskEntry[1]] = 0
                                    else
                                        if firstByte ~= -2 then
                                            p.pos = p.pos - 1
                                        end

                                        valueTable[subMaskEntry[1]] = p:DecodeF()
                                    end
                                else
                                    local maxReadLength = 8
                                    local shiftOffset = 0
                                    local readValue = 0

                                    repeat
                                        if maxReadLength == 0 then break end

                                        local readByte = Packet.unsignedToSigned(p:Decode1(), 1)

                                        readValue = bit32.bor(readValue, bit32.lshift(bit32.band(readByte, 0x7F), shiftOffset))

                                        maxReadLength = maxReadLength - 1
                                        shiftOffset = shiftOffset + 7
                                    until readByte >= 0

                                    valueTable[subMaskEntry[1]] = readValue
                                end
                            end
                        end

                        if p.pos ~= startPos + sectionLength then
                            p.pos = startPos + sectionLength

                            packetResult.decodedSuccessfully = false
                        end
                    end
                end
            end

            table.insert(packetResult.updates, valueTable)
        end

        return packetResult
    end
}